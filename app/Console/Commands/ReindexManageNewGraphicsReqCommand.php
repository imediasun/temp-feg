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
                        'filter' => 'lowercase'
                    ],
                ],
                'tokenizer' => [
                    'ngram_tokenizer' => [
                        'type' => 'nGram',
                        'min_gram' => 4,
                        'max_gram' => 4,
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
                        "fielddata"=> true
                    ],
                    'media_type' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'location_name' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'status' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'need_by_date_text' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'request_user' => [
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

$orders=Managenewgraphicrequests::with('status')->with('receiveUser')->get();
    foreach ($orders as $model) {
            $mas = $model->toSearchArray();

        if(!null==($model->location)){
        //dump($model->location->location_name);
            $mas['location_name'] = $model->location->location_name;
        }
        else{
            dump('F');
        }

        if(!null==($model->status)){
            //dump($model->status->status);
            $mas['status'] = $model->status->status;
        }

        if(isset($model->receiveUser) && $model->receiveUser!=null) {
                $mas['request_user'] = $model->receiveUser->first_name . "." . $model->receiveUser->last_name;
                dump($mas['request_user']);

        }

            $need=explode('-',$mas['need_by_date']);
        $mas['need_by_date_text']=$need[1].'/'.$need[2].'/'.$need[0];
       // dump($mas['need_by_date_text']);

        $request_date=explode('-',$mas['request_date']);
        $mas['request_date_text']=$request_date[1].'/'.$request_date[2].'/'.$request_date[0];
        //dump($mas['request_date_text']);

        $approve_date=explode('-',$mas['approve_date']);
        $mas['approve_date_text']=$approve_date[1].'/'.$approve_date[2].'/'.$approve_date[0];
        //dump($mas['approve_date_text']);

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
