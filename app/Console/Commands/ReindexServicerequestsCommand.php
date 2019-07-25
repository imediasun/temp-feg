<?php

namespace App\Console\Commands;

use App\Models\Servicerequests;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexServicerequestsCommand extends Command
{
    protected $name = "search:reindex_servicerequests";
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
            'index' => 'elastic_servicerequests',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_servicerequests',
            'type'=>'servicerequests',
            'body'=>[
                'properties'=>[
                    'Description' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'issue_type' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'TicketID' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'Subject' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'need_by_date_text' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]

                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_servicerequests'])) {
            $this->search->indices()->delete(['index'=>'elastic_servicerequests']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

        $orders=Servicerequests::with('getFollowers')->get();
        foreach ($orders as $model) {
            if($model->need_by_date=='0000-00-00'){
                $model->need_by_date='1978-01-01';
            }
            if($model->date_ordered=='0000-00-00'){
                $model->date_ordered='1978-01-01';
            }

            $mas = $model->toSearchArray();
            if(isset($model->getFollowers) && $model->getFollowers!=null){
                $user= \App\Models\Core\Users::where('id',$model->getFollowers->user_id)->first();
                if($user){
                    $mas['updated_by']=$user->first_name." ".$user->last_name;
                    dump($mas['updated_by']);}

                $need=explode('-',$mas['need_by_date']);
                $mas['need_by_date_text']=$need[1].'/'.$need[2].'/'.$need[0];
                dump($mas['need_by_date_text']);
            }
            //dump($mas['TicketID']);
            $this->search->index([
                'index' => 'elastic_servicerequests',
                'type' => 'servicerequests',
                'id' => $mas['TicketID'],
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
