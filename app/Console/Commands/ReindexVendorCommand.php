<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexVendorCommand extends Command
{
    protected $name = "search:reindex_vendor";
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
            'index' => 'elastic_vendor',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_vendor',
            'type'=>'vendor',
            'body'=>[
                'properties'=>[
                    'vendor_name' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                   'contact' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'game_contact_name' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "vendor_ap_contact_name"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "email"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_vendor'])) {
            $this->search->indices()->delete(['index'=>'elastic_vendor']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$orders=Vendor::get();
    foreach ($orders as $model) {
/*    if($model->date_received=='0000-00-00'){
        $model->date_received='1978-01-01';
    }
            if($model->date_ordered=='0000-00-00'){
                $model->date_ordered='1978-01-01';
            }*/

            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
           /* if(!null==($model->vendor)){
                $mas['vendor_name'] = $model->vendor->vendor_name;
            }*/


            $this->search->index([
                'index' => 'elastic_vendor',
                'type' => 'vendor',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
