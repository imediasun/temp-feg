<?php

namespace App\Repositories\Orders;

use App\Models\Order;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchOrdersRepository implements OrdersRepository
{
    private $search;

    public function __construct(Client $client) {
        $this->search = $client;
    }

    public function search($query = "")
    {
        $items = $this->searchOnElasticsearch($query);
        return $this->buildCollection($items);
    }

    public function addToIndexIds($id){
        $order=Order::where('id',$id)->with('orderedBy')->with('receiveLocation')->with('receiveVendor')->get();

        foreach($order as $model){
        $mas = $model->toSearchArray();
        if($model::getProductInfo(strip_tags($model->id))){
            $results = $model::getProductInfo(strip_tags($model->id));
            $info = '';
            foreach($results as $r){
                if(!isset($r->sku)){
                    $sku = " (SKU: No Data) ";
                }else{
                    $sku = " (SKU: ".$r->sku.")";
                }

                $info = $info .'('.$r->qty.') '.$r->item_name.' '.\CurrencyHelpers::formatPrice($r->total).$sku. ';';
            }
            $mas['product_info'] = $info;
            //var_dump($mas['product_info']);
        }
        else{
            //var_dump('not_product_info');
        }

        if(isset($model->receiveLocation) && null!=($model->receiveLocation) && isset($model->receiveLocation->location_name) && null!=$model->receiveLocation->location_name){
            $mas['location_name'] = $model->receiveLocation->location_name;
        }
        if(isset($model->receiveVendor) && null!=($model->receiveVendor)&& isset($model->receiveVendor->vendor_name) && null!=$model->receiveVendor->vendor_name){
            $mas['vendor_name'] = $model->receiveVendor->vendor_name;
        }
        if(isset($model->orderedBy) && null!=($model->orderedBy)&& isset($model->orderedBy->first_name) && null!=$model->orderedBy->first_name){
            $mas['orderedBy'] = $model->orderedBy->first_name.".".$model->orderedBy->last_name;
            //dump($mas['orderedBy']);
        }

        $updated_at=explode(' ',$mas['updated_at']);
        $need=explode('-',$updated_at[0]);
        if(isset($need[1])){
            $mas['updated_at_string']=$need[1].'/'.$need[2].'/'.$need[0];
            //dump($mas['updated_at_string']);
        }

        $api_created_at=explode(' ',$mas['api_created_at']);
        $need_api_created_at=explode('-',$api_created_at[0]);
        if(isset($need_api_created_at[1])){
            $mas['api_created_at_string']=$need_api_created_at[1].'/'.$need_api_created_at[2].'/'.$need_api_created_at[0];
            //dump($mas['api_created_at_string']);
        }

        $this->search->index([
            'index' => 'elastic_order',
            'type' => 'order',
            'id' => $id,
            'body' => $mas,
        ]);
        }

    }

    public function searchOnElasticsearch($query){
        $instance = new Order;
        $items = $this->search->search([
                'index' => 'elastic_order',
                'type' => 'order',
                "size"=>200,
                'body'=>[
                    'query'=>[
                        "multi_match"=>[
                            "fields"=>["po_number^20","id","product_info","location_name^6","vendor_name","po_notes^18","notes","tracking_number","orderedBy","updated_at_string^15","api_created_at_string^14"],
                            "query"=>$query
                        ]
                    ],
                    "highlight" => [
                        "pre_tags"  => "<b style='color:#da4f49'>",
                        "post_tags" => "</b>",
                        "fields" => [
                            "po_number" => new \stdClass(),
                            "order_description"=> new \stdClass(),
                            "id" => new \stdClass(),
                            "notes" => new \stdClass(),
                            "location_name" => new \stdClass(),
                            "vendor_name" => new \stdClass(),
                            "product_info" => new \stdClass(),
                            "po_notes" => new \stdClass(),
                            "tracking_number" => new \stdClass(),
                            "orderedBy" => new \stdClass(),
                            "updated_at_string" => new \stdClass(),
                            "api_created_at_string" => new \stdClass(),

                        ]
                    ]]]


        );

        return $items;

    }

    private function buildCollection(array $items)
    {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
         */


        foreach($items['hits']['hits'] as $k=>$value){
            if(is_array($value)){

                foreach($value as $key=>$val) {
                    //dump('=>',$val['highlight']);
                    if ($key == 'highlight') {
                        //dump($val);
                        foreach($val as $kr=>$vs){
                            $items['hits']['hits'][$k]['_source'][$kr]=$val[$kr][0];
                        }

                    }
                }
            }

        }

        //dump('===',$items['hits']);

        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];

        $sources = array_map(function ($source) {
            // The hydrate method will try to decode this
            // field but ES gives us an array already.

            //$source['vendor_description'] = json_encode($source['vendor_description']);
            return $source;
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return Order::hydrate($sources);
    }
}