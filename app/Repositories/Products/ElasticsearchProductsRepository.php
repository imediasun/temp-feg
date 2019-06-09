<?php

namespace App\Repositories\Products;

use App\Models\Product;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchProductsRepository implements ProductsRepository
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
        $instance = new Product;
        $items = $this->search->search([
                'index' => 'elastic',
                'type' => 'article',
                'body'=>[
                    'query'=>[
                        "multi_match"=>[
                            "fields"=>["title","body","tags"],
                            "query"=>$query
                        ]
                    ],
                    "highlight" => [
                        "pre_tags"  => "<b>",
                        "post_tags" => "</b>",
                        "fields" => [
                            "tags" => new \stdClass(),
                            "title"=> new \stdClass(),
                            "body" => new \stdClass()

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
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];

        $sources = array_map(function ($source) {
            // The hydrate method will try to decode this
            // field but ES gives us an array already.
            $source['vendor_description'] = json_encode($source['vendor_description']);
            return $source;
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return Product::hydrate($sources);
    }
}