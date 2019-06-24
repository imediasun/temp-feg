<?php

namespace App\Console\Commands;

use App\Models\Productlog;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexProductReservedQtyLogCommand extends Command
{
    protected $name = "search:reindex_productreservedqtylog";
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
            'index' => 'elastic_productreservedqtylog',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_productreservedqtylog',
            'type'=>'productreservedqtylog',
            'body'=>[
                'properties'=>[
                    'vendor_description' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_productreservedqtylog'])) {
            $this->search->indices()->delete(['index'=>'elastic_productreservedqtylog']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

        $orders=Productlog::get();
        foreach ($orders as $model) {
            $mas = $model->toSearchArray();
   /*        dump($model->product);
            if(!null==($model->product)){
                dump($model->product->vendor_description);
                $mas['vendor_description'] = $model->product->vendor_description;
            }
            else{
                dump('F');
            }*/

            $this->search->index([
                'index' => 'elastic_productreservedqtylog',
                'type' => 'productreservedqtylog',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
