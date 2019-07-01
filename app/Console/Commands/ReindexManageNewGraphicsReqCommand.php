<?php

namespace App\Console\Commands;

use App\Models\Managenewgraphicrequests;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexManageNewGraphicsReqCommand extends Command
{
    protected $name = "search:reindex_manage_new_graphics_req";
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
            'index' => 'elastic_manage_new_graphics_req',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_manage_new_graphics_req',
            'type'=>'manage_new_graphics_req',
            'body'=>[
                'properties'=>[
                    'description' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'media_type' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'location_name' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_manage_new_graphics_req'])) {
            $this->search->indices()->delete(['index'=>'elastic_manage_new_graphics_req']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$orders=Managenewgraphicrequests::get();
    foreach ($orders as $model) {
            $mas = $model->toSearchArray();

        if(!null==($model->location)){
        dump($model->location->location_name);
            $mas['location_name'] = $model->location->location_name;
        }
        else{
            dump('F');
        }


            $this->search->index([
                'index' => 'elastic_manage_new_graphics_req',
                'type' => 'manage_new_graphics_req',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
