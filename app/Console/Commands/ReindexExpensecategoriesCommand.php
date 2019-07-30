<?php

namespace App\Console\Commands;

use App\Models\Expensecategories;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexExpensecategoriesCommand extends Command
{
    protected $name = "search:reindex_expensecategories";
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
            'index' => 'elastic_expensecategories',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_expensecategories',
            'type'=>'expensecategories',
            'body'=>[
                'properties'=>[
                    'order_type_name' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'mapped_expense_category' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                ]
            ]


        ];


        if ($this->search->indices()->exists(['index'=>'elastic_expensecategories'])) {
            $this->search->indices()->delete(['index'=>'elastic_expensecategories']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$expensecategories=Expensecategories::get();
        foreach ($expensecategories as $model) {
            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
            if(!null==($model->orderType)){
                $mas['order_type_name'] = $model->orderType->order_type;
                dump($mas['order_type_name']);
            }
            $this->search->index([
                'index' => 'elastic_expensecategories',
                'type' => 'expensecategories',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
