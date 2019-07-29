<?php

namespace App\Console\Commands;

use App\Models\Product;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexCommand extends Command
{
    protected $name = "search:reindex";
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
            'index' => 'elastic_product',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_product',
            'type'=>'product',
            'body'=>[
                'properties'=>[
                    'item_description' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'vendor_description' => [
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
                    ],
                    "updated_at_string"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]

                ]
            ]


        ];


        if ($this->search->indices()->exists(['index'=>'elastic_product'])) {
            $this->search->indices()->delete(['index'=>'elastic_product']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////


        foreach (Product::get() as $model) {

        $mas=$model->toSearchArray();
            if(/*isset($model->receiveVendor) && null!=($model->receiveVendor)&& isset($model->receiveVendor->vendor_name) && */null!=$model->receiveVendor->vendor_name){
                $mas['vendor_name'] = $model->receiveVendor->vendor_name;
                dump($mas['vendor_name']);
            }

            $updated_at=explode(' ',$mas['updated_at']);
            $need=explode('-',$updated_at[0]);
            if(isset($need[1])){
                $mas['updated_at_string']=$need[1].'/'.$need[2].'/'.$need[0];
                dump($mas['updated_at_string']);
            }

            $this->search->index([
                'index' => 'elastic_product',
                'type' => 'product',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
