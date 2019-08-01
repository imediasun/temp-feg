<?php

namespace App\Repositories\Managenewgraphicrequests;

use App\Models\Managenewgraphicrequests;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchManagenewgraphicrequestsRepository implements ManagenewgraphicrequestsRepository
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
        $instance = new Managenewgraphicrequests;
        $items = $this->search->search([
                'index' => 'elastic_manage_new_graphics_req',
                'type' => 'manage_new_graphics_req',
                "size"=>2000,
                'body'=>[
                    'query'=>[
                        "multi_match"=>[
                            "fields"=>["description","media_type","request_user","location_name","status","need_by_date_text^6","request_date_text^5","approve_date_text^4"],
                            "query"=>$query
                        ]
                    ],
                    "highlight" => [
                        "pre_tags"  => "<b style='color:#da4f49'>",
                        "post_tags" => "</b>",
                        "fields" => [
                            "description" => new \stdClass(),
                            "media_type" => new \stdClass(),
                            "location_name" => new \stdClass(),
                            "status" => new \stdClass(),
                            "need_by_date_text" => new \stdClass(),
                            "request_date_text" => new \stdClass(),
                            "approve_date_text" => new \stdClass(),
                            "request_user" => new \stdClass(),
                        ]
                    ],
                     "sort" => [
            "_script" => [
                "type" => "number",
            "script" => [
                 "lang"=> "expression",
                "source"=> "doc['description'].value.length() * params.factor",
                "params" => [
                        "factor" => 1.1
                ]
            ],

            "order" => "desc"
        ]
    ]

                ]]


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
        return Managenewgraphicrequests::hydrate($sources);
    }
}