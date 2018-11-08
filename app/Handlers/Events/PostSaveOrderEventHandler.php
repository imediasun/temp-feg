<?php

namespace App\Handlers\Events;

use App\Events\PostSaveOrderEvent;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\product;
use App\Models\ReservedQtyLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use \App\Models\Sximo\Module;

class PostSaveOrderEventHandler
{

    private static $module = 'order';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostSaveOrderEvent $event
     * @return void
     */
    public function handle(PostSaveOrderEvent $event)
    {
        $item = (object)$event->order_item;
        $product = product::find($item->product_id);
        //fix added by arslan on 4/3/2018 for Error Report # 134
        if(is_null($product)){
            return ;
        }
        if ($product->is_reserved == 1) {

            $ReservedProductQtyLogObj = ReservedQtyLog::where('order_id', $item->order_id)
                ->where('variation_id', $product->variation_id)
                ->where('adjustment_type', 'negative')
                ->orderBy('id', 'DESC')
                ->first();
            if($item->is_broken_case == 1){
                $item->qty = ceil($item->qty/$item->qty_per_case);
            }

            if($item->pre_is_broken_case == 1){
                $item->prev_qty = ceil($item->prev_qty/$item->qty_per_case);
            }


            if ($ReservedProductQtyLogObj and $item->prev_qty) {
                $adjustmentAmount = ($product->reserved_qty + $item->prev_qty) - $item->qty;
                if($item->prev_qty > $item->qty){
                    $qty = ($item->qty - $item->prev_qty) < 0 ? ( ($item->qty - $item->prev_qty) * -1 ):($item->qty - $item->prev_qty);
                    if($item->prev_qty != $item->qty) {
                        self::setPositiveAdjustement($item, $product, "positive", $qty);
                    }
                }else{

                    $qty = ($item->qty - $item->prev_qty) < 0 ? ( ($item->qty - $item->prev_qty) * -1 ):($item->qty - $item->prev_qty);
                    if($item->prev_qty != $item->qty) {
                        self::setPositiveAdjustement($item, $product, "negative", $qty);
                    }
                }

            } else {
                $adjustmentAmount = $product->reserved_qty - $item->qty;

                $qty = ($item->qty) < 0 ? ( ($item->qty) * -1 ):($item->qty);

                self::setPositiveAdjustement($item,$product,"negative",$qty);
            }

            $inactive = 0;
            if ($product->allow_negative_reserve_qty != 1 and $adjustmentAmount == 0) {
                $inactive = 1;
            } else {
                $inactive = 0;
            }
            $sendEmail = (int) $product->send_email_alert;
            $attributes = ['reserved_qty' => $adjustmentAmount, 'inactive' => $inactive];
            if($sendEmail == 0){
                $attributes['send_email_alert'] = 1;
            }
            $product->updateProduct($attributes, true);
            $product = product::find($product->id);

            Log::info("-----------------Email Flag = ".$product->send_email_alert);

            $product->save();
            Log::info("----------------Email Flag after sending email= ".$product->send_email_alert);
            if($inactive == 1){
                // When product with reserved quantity becomes inactive due to not allowing negative quantities:
                /* > Hello FEG Team,
                 > <br>
                 > The following product has become inactive due to a lack of remaining reserve quantity.
                 >
                 > Product Name:
                 > Product SKU:
                 > Reserved Quantity:
                 >
                 >*/
                $message = 'Hello FEG Team,';
                $message .='<br><br>';
                $message .='The following product has become inactive due to a lack of remaining reserve quantity.<br>';
                $message .='<br><br>';
                $message .='Product Name: '.$product->vendor_description.'<br>';
                $message .='Product SKU: '.$product->sku.'<br>';
                $message .='Reserved Quantity: '.$adjustmentAmount.'<br>';
                self::sendProductReservedQtyEmail($message,$sendEmail, $product);
            }
            if ($adjustmentAmount <= $product->reserved_qty_limit && $inactive == 0) {
                /* When reserved quantity par amount is met or exceeded (reserve quantity reduced to par amount or less):

                 > Hello FEG Team,
                 >
                 > The following product has met or exceeded it's par amount.
                 >
                 > Product Name:
                 > Product SKU:
                 > Reserved Qty Par Amount:
                 > Remaining Reserved Quantity:
                 >
                  */

                $message = 'Hello FEG Team,';
                $message .='<br><br>';
                $message .='The following product has met or exceeded it\'s par amount.<br>';
                $message .='<br><br>';
                $message .='Product Name: '.$product->vendor_description.'<br>';
                $message .='Product SKU: '.$product->sku.'<br>';
                $message .='Reserved Qty Par Amount: '.$product->reserved_qty_limit.'<br>';
                $message .='Remaining Reserved Quantity: '.$adjustmentAmount."<br>";
                self::sendProductReservedQtyEmail($message,$sendEmail, $product);

            }
        }
    }
    public static function setPositiveAdjustement($item,$product,$type,$qty){
        $reservedLogData = [
            "product_id" => $item->product_id,
            "order_id" => $item->order_id,
            "adjustment_amount" => $qty,
            "adjustment_type" => $type,
            "variation_id" => $product->variation_id,
            "adjusted_by" => \AUTH::user()->id,
        ];

        $reservedQtyLog = new ReservedQtyLog();
        $reservedQtyLog->insert($reservedLogData);
    }

    public static function sendProductReservedQtyEmail($message,$sendEmail, $product)
    {
        $receipts = self::getReceiversEmailsArray($product);
        /*An email alert will be sent when the Reserved Quantity reaches an amount defined per-product. */
        if($sendEmail == 0) {
            FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
                'subject' => "Product Reserved Quantity Email Alert",
                'message' => $message,
                'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
            )));
        }
    }


    public static function getReceiversEmailsArray($product)
    {
        $productTypeId = $product->prod_type_id;

        $module_id = Module::name2id(self::$module);
        $pass = \FEGSPass::getMyPass($module_id);

        $dataOptionsString = $pass['calculate price according to case price']->data_options;
        $dataOptionsArray = explode(',', $dataOptionsString);
        $isTest = env('APP_ENV', 'development') !== 'production' ? true : false;

        if(!in_array($productTypeId, $dataOptionsArray))
            $receiversEmailAddresses = FEGSystemHelper::getSystemEmailRecipients("Product Reserved Quantity Email For Non Merchandise", null, $isTest);
        else
            $receiversEmailAddresses = FEGSystemHelper::getSystemEmailRecipients("Product Reserved Quantity Email For Merchandise", null, $isTest);

        return $receiversEmailAddresses;
    }

}
