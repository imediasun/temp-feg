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

    public function searchOnElasticsearch($query){
        $instance = new Order;
        $items = $this->search->search([
                'index' => 'elastic_order',
                'type' => 'order',
                "size"=>200,
                'body'=>[
                    'query'=>[
                        "multi_match"=>[
                            "fields"=>["po_number","id","product_info","location_name^6","vendor_name","po_notes","notes","tracking_number","orderedBy","updated_at_string^15","api_created_at_string^14"],
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