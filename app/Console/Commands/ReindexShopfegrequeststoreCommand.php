<?php

namespace App\Console\Commands;

use App\Models\Shopfegrequeststore;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexShopfegrequeststoreCommand extends Command
{
    protected $name = "search:reindex_shopfegrequeststore";
    protected $description = "Indexes all articles to elasticsearch";
    private $search;

    public function __construct(Client $search)
    {
        parent::__construct();

        $this->search = $search;
    }

    public function handle()
    {
        $this->info('Indexing all articles. Might take a while...');

        //// CREATE INDEX FOR SEARCH
        $setting = [
            'analysis' => [
                'analyzer' => [
                    'ngram_analyzer_with_filter' => [
                        'tokenizer' => 'ngram_tokenizer',
                        'filter' => 'lowercase, snowball'
                    ],
                ],
                'tokenizer' => [
                    'ngram_tokenizer' => [
                        'type' => 'nGram',
                        'min_gram' => 3,
                        'max_gram' => 3,
                        'token_chars' => ['letter', 'digit', 'whitespace', 'punctuation', 'symbol']
                    ],

                ],
            ]
        ];

        $param = [
            'index' => 'elastic_shopfegrequeststore',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_shopfegrequeststore',
            'type'=>'shopfegrequeststore',
            'body'=>[
                'properties'=>[
                   'item_description' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'sku' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "vendor_name"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_shopfegrequeststore'])) {
            $this->search->indices()->delete(['index'=>'elastic_shopfegrequeststore']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$orders=Shopfegrequeststore::get();
    foreach ($orders as $model) {
/*    if($model->date_received=='0000-00-00'){
        $model->date_received='1978-01-01';
    }
            if($model->date_ordered=='0000-00-00'){
                $model->date_ordered='1978-01-01';
            }*/

            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
            if(!null==($model->vendor)){
                //dump($model->vendor->vendor_name);
                $mas['vendor_name'] = $model->vendor->vendor_name;
            }
            else{
                dump('F');
            }

            $this->search->index([
                'index' => 'elastic_shopfegrequeststore',
                'type' => 'shopfegrequeststore',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
