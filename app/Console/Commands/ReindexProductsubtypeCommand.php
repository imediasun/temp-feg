<?php

namespace App\Console\Commands;

use App\Models\Productsubtype;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexProductsubtypeCommand extends Command
{
    protected $name = "search:reindex_productsubtype";
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
                        'min_gram' => 1,
                        'max_gram' => 3,
                        'token_chars' => ['letter', 'digit', 'whitespace', 'punctuation', 'symbol']
                    ],

                ],
            ]
        ];

        $param = [
            'index' => 'elastic_productsubtype',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_productsubtype',
            'type'=>'productsubtype',
            'body'=>[
                'properties'=>[
                   'product_type' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'id' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "order_type"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_productsubtype'])) {
            $this->search->indices()->delete(['index'=>'elastic_productsubtype']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$orders=Productsubtype::get();
    foreach ($orders as $model) {
            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
            if(!null==($model->order_type)){
                dump($model->order_type->order_type);
                $mas['order_type'] = $model->order_type->order_type;
            }
            else{
                dump('F');
            }
        $mas['id_column']=strval($mas['id']);
            dump($mas['id_column']);

            $this->search->index([
                'index' => 'elastic_productsubtype',
                'type' => 'productsubtype',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
