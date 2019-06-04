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

    private function searchOnElasticsearch($query)
    {
        $instance = new Product;
        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),

            'body' => [
                "query" => [
                       "bool" => [
                           "should" => [
                            ["regexp" => [
                               "tags" => [
                                   "value" => ".{2,8}" . $query . ".*",
                            ]
                            ],
                                ],
                            ["wildcard" => [
                               "tags" => [
                                   "value" => "*" . $query . "*",
                                   "boost" => 1.0,
                                   "rewrite" => "constant_score"
                                    ]
                                ]
                            ]
                        ]],
                    ], "highlight" => [
                    "fields" => [
                        "tags" => ["type" => "plain"]
                    ]
                ]
          ]]);

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
            $source['tags'] = json_encode($source['tags']);
            return $source;
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return Product::hydrate($sources);
    }
}