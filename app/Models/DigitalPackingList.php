<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
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
    public function saveOrdUpdateDPL($data,$id=0){
        $id = $this->insertRow($data, $id);
        return self::find($id);
    }

    public function getNameAttribute(){
       return $this->order->po_number."_".$this->id.".dpl";
    }

    public function order(){
        return $this->belongsTo("App\Models\Order");
    }
    public function getDPLFileData(){
        $newLine = "\r\n";
        $fileContent = $this->order->location_id . ", " . $this->order->po_number . $newLine;
        foreach ($this->order->contents as $product) {
            Log::info("DPL Product Name:".$product->item_name);
            $itemId = $product->upc_barcode;
            $itemName = $this->truncateString($product->item_name);
            $module = new OrderController();
            $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
            $order_types = $pass['calculate price according to case price']->data_options;
            $order_types = explode(",", $order_types);
            $unitTypeUOM = 'Each';
            $price = $product->price; // ordered product unit price

            if (in_array($this->order->order_type_id, $order_types)) {
                $unitTypeUOM = 'Case';
                $price = $product->case_price; // ordered product case price
            }

            $tickets = $product->ticket_value;
            $QtyPerCase = $product->qty_per_case;
            $productType = $product->prod_type_id;

            $orderTypes = [
                Order::ORDER_TYPE_OFFICE_SUPPLIES => 'OffSuppl',
                Order::ORDER_TYPE_REDEMPTION => 'RedPrize',
                Order::ORDER_TYPE_INSTANT_WIN_PRIZE => 'InstWin',
                Order::ORDER_TYPE_PARTY_SUPPLIES => 'PartySup',
                Order::ORDER_TYPE_UNIFORM => 'Uniforms'
            ];
            //simple logic
            if(isset($orderTypes[$productType])){
                $productType = $orderTypes[$productType];
            }

            if ($this->order->location->debit_type_id == 1) {
                //Sacoa type
                $fileContent .= $itemId . "," . $itemName . "," . $unitTypeUOM . "," . $product->item_received . "," . $price . "," . $tickets . "," . $QtyPerCase . "," . $product->price . $newLine;
            } else {
                //Embed Type
                $fileContent .= $itemId . "," . $itemName . "," . $unitTypeUOM . "," . $product->item_received . "," . $price . "," . $tickets . "," . $QtyPerCase . "," . $product->price . "," . $productType . $newLine;
            }
        }
        return $fileContent;
    }

    public function isFileNeedToBeRegenerated(Order $order){

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


    public function truncateString($string)
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
    public function isOrderReceived($order_id)
    {
        $order = Order::where("id",'=',$order_id)->first();
            $orderedQty = 0;
            $receivedQty = -1;
        if($order) {
            if ($order->contents) {
                $orderedQty = $order->contents->sum('qty');
            }
            if ($order->orderReceived) {
                $receivedQty = $order->orderReceived->sum('quantity');
            }
        }
            if ($orderedQty == $receivedQty && $order->is_freehand == 0) {
                return $flagCheck = true;
            } else {
                return $flagCheck = false;
            }

    }
    public function getTruncateString($string)
    {
        if (strlen($string) < 50 || strlen($string) == 50 ) {
            return $string;
        }
        else{
            $string = substr($string,0,50);
            return $string;
        }
    }
}
