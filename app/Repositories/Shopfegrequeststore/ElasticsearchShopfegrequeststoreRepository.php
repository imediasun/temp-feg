<?php

namespace App\Repositories\Shopfegrequeststore;

use App\Models\Shopfegrequeststore;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchShopfegrequeststoreRepository implements ShopfegrequeststoreRepository
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
        $instance = new Shopfegrequeststore;
        $items = $this->search->search([
                'index' => 'elastic_shopfegrequeststore',
                'type' => 'shopfegrequeststore',
                "size"=>200,
                'body'=>[
                    'query'=>[
                        "multi_match"=>[
                            "fields"=>["vendor_description^6","item_description^5","vendor_name^4","sku^3"],
                            "query"=>$query
                        ]
                    ],
                    "highlight" => [
                        "pre_tags"  => "<b style='color:#da4f49'>",
                        "post_tags" => "</b>",
                        "fields" => [
                            "vendor_description" => new \stdClass(),
                            "item_description" => new \stdClass(),
                            "sku"=> new \stdClass(),
                            "vendor_name" => new \stdClass()

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
        return Shopfegrequeststore::hydrate($sources);
    }
}