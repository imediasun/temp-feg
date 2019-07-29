<?php

namespace App\Repositories\Managefegrequeststore;

use App\Models\Managefegrequeststore;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchManagefegrequeststoreRepository implements ManagefegrequeststoreRepository
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
        $instance = new Managefegrequeststore;
        $items = $this->search->search([
                'index' => 'elastic_managefegrequeststore',
                'type' => 'managefegrequeststore',
                "size"=>200,
                'body'=>[
                    'query'=>[
                        "multi_match"=>[
                            "fields"=>["item_name","sku^6","vendor_name","notes","location_name","id^7","product_type","product_subtype","user^7"],
                            "query"=>$query
                        ]
                    ],
                    "highlight" => [
                        "pre_tags"  => "<b style='color:#da4f49'>",
                        "post_tags" => "</b>",
                        "fields" => [
                            "item_name" => new \stdClass(),
                            "sku"=> new \stdClass(),
                            "vendor_name" => new \stdClass(),
                            "notes" => new \stdClass(),
                            "location_name" => new \stdClass(),
                            "id" => new \stdClass(),
                            "product_type" => new \stdClass(),
                            "product_subtype" => new \stdClass(),
                            "user" => new \stdClass()

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
        return Managefegrequeststore::hydrate($sources);
    }
}