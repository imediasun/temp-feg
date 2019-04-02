<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\File;
use App\Models\Order;
use Carbon\Carbon;
use Log;

class DigitalPackingList extends Sximo
{
    protected $table = 'digital_packing_lists';
    protected $primaryKey = 'id';
    const DPL_FILE_PATH  = 'uploads/dpl-files/';

    public function __construct() {
        parent::__construct();

    }

    public function fileExists(){

    }
    public function saveOrUpdateDPL($data, $id=0){
        $id = $this->insertRow($data, $id);
        return self::find($id);
    }

    public function getNameAttribute(){
       return str_replace("-","",$this->order->po_number).".dpl";
    }



    public function order(){
        return $this->belongsTo("App\Models\Order");
    }
    public function getDPLFileData(){
        $newLine = "\r\n";
        $fileContent = $this->order->location_id . ", " . str_replace('-',"",$this->order->po_number) . $newLine;
        $totalItems = $this->order->contents()->count();
        $countIndex = 0;
        $redemptionPrizeProducts = $this->order->filterRedemptionTypeProducts();
        foreach ($redemptionPrizeProducts as $product) {
            $countIndex++;
            Log::info("DPL Product Name:".$product->item_name);
            $itemId = $product->upc_barcode;
            $itemName = $this->cleanAndTruncateString($product->item_name);
            $sku = $product->sku;

            $unitTypeUOM = $this->order->getUnitOfMeasurementForOrderType();
            $price =($unitTypeUOM == "CASE") ? $price = \CurrencyHelpers::formatPrice($product->case_price/$product->qty_per_case, $decimalPlaces = 5,  false,  '', $dec_point = '.',  false) : $product->price;

            $tickets = $product->ticket_value;
            $qtyPerCase = $product->qty_per_case;

            $orderTypes = [
                Order::ORDER_TYPE_OFFICE_SUPPLIES => 'OffSuppl',
                Order::ORDER_TYPE_REDEMPTION => 'RedPrize',
                Order::ORDER_TYPE_INSTANT_WIN_PRIZE => 'InstWin',
                Order::ORDER_TYPE_MARKETING => 'Marketing',
                Order::ORDER_TYPE_UNIFORM => 'Uniforms'
            ];
            $itemName = \SiteHelpers::removeSpecialCharacters($itemName);
            $productType = isset($orderTypes[$product->prod_type_id]) ? $orderTypes[$product->prod_type_id]:$product->prod_type_id;

            $newLine = ($countIndex == $totalItems) ? '':$newLine;
            $unitTypeUOMUpdated = ((strtolower($unitTypeUOM) == 'case') ? ($product->is_broken_case == 0) ? 'EACH':'EACH':'EACH');
            $quantityReceived = ((strtolower($unitTypeUOM) == 'case') ? ($product->is_broken_case == 0) ? ($product->item_received * $qtyPerCase): $product->item_received : $product->item_received);
            if ($this->order->location->debit_type_id == Location::LOCATION_TYPE_SACOA) {
                $fileContent .= implode(",",[$itemId, $sku,$tickets, $itemName, $productType, '', '', $price, '','', $quantityReceived]) . $newLine;
            } else {
                $itemName = \SiteHelpers::truncateStringToSpecifiedLimit($itemName,50);
                $fileContent .= $itemId . "," . $itemName . "," . $unitTypeUOMUpdated . "," .$quantityReceived . "," . $price . "," . $tickets . "," . '1' . "," . $product->price . "," . $productType . $newLine;
            }
        }
        return $fileContent;
    }

    public function isFileNeedToBeRegenerated(Order $order){
///////////
        if(Carbon::parse($order->updated_at)->gt(Carbon::parse($this->created_at)) || $order->location->debit_type_id != $this->type_id){
            return true;
        }else{
            return false;
        }

    }
    public function saveFile($fileContent){
        File::put( public_path(self::DPL_FILE_PATH). $this->name, $fileContent);
        Log::info("DPL File Created:".public_path(self::DPL_FILE_PATH).$this->name);
    }


    public function cleanAndTruncateString($string, $length = 50)
    {
        $string = str_replace(["&",",",'"'],"",$string);
        return $string; //$this->truncateString($string, $length);
    }

    public function getTruncateString($string)
    {
        $string = str_replace(["&",",",'"'],"",$string);
        if (strlen($string) < 50 || strlen($string) == 50 ) {
            return $string;
        }
        else{
            $string = substr($string,0,50);
            return $string;
        }
    }

}
