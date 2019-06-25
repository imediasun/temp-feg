<?php

namespace App\Console\Commands;

use App\Models\Managefegrequeststore;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexManagefegrequeststoreCommand extends Command
{
    protected $name = "search:reindex_managefegrequeststore";
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
            'index' => 'elastic_managefegrequeststore',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_managefegrequeststore',
            'type'=>'managefegrequeststore',
            'body'=>[
                'properties'=>[
                    'item_name' => [
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


        if ($this->search->indices()->exists(['index'=>'elastic_managefegrequeststore'])) {
            $this->search->indices()->delete(['index'=>'elastic_managefegrequeststore']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$orders=Managefegrequeststore::get();
    foreach ($orders as $model) {

        if($model->request_date=='0000-00-00'){
            $model->request_date='1978-01-01';
        }
            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
            if(!null==($model->vendor_item)){
                dump($model->vendor_item->vendor_name);
                $mas['vendor_name'] = $model->vendor_item->vendor_name;
            }
        if(!null==($model->product)){
            //dump($model->product->vendor_description);
            $mas['item_name'] = $model->product->vendor_description;
        }


            $this->search->index([
                'index' => 'elastic_managefegrequeststore',
                'type' => 'managefegrequeststore',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
