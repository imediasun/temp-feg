<?php

namespace App\Console\Commands;

use App\Models\Order;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use function print_r;

class ReindexOrderCommand extends Command
{
    protected $name = "search:reindex_order";
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
            'index' => 'elastic_order',
            'body' => [
                'settings' => $setting,
            ]
        ];


        $mapping = [
            'index' =>'elastic_order',
            'type'=>'order',
            'body'=>[
                'properties'=>[
                    'po_number' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'product_info' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],

                    'order_description' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'id' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'notes' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "location_name"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "vendor_name"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "po_notes"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ]
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_order'])) {
            $this->search->indices()->delete(['index'=>'elastic_order']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

        $orders=Order::withTrashed()->with('receiveLocation')->with('receiveVendor')->get();
        foreach ($orders as $model) {
            if($model->date_received=='0000-00-00'){
                $model->date_received='1978-01-01';
            }
            if($model->date_ordered=='0000-00-00'){
                $model->date_ordered='1978-01-01';
            }

            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
            if($model::getProductInfo($model->id)){
                $results = $model::getProductInfo($model->id);
                $info = '';
                foreach($results as $r){
                    if(!isset($r->sku)){
                        $sku = " (SKU: No Data) ";
                    }else{
                        $sku = " (SKU: ".$r->sku.")";
                    }

                    $info = $info .'('.$r->qty.') '.$r->item_name.' '.\CurrencyHelpers::formatPrice($r->total).$sku. ';';
                }
                $mas['product_info'] = $info;
                dump($mas['product_info']);
            }

            if(isset($model->receiveLocation) && null!=($model->receiveLocation) && isset($model->receiveLocation->location_name) && null!=$model->receiveLocation->location_name){
                $mas['location_name'] = $model->receiveLocation->location_name;
            }
            if(isset($model->receiveVendor) && null!=($model->receiveVendor)&& isset($model->receiveVendor->vendor_name) && null!=$model->receiveVendor->vendor_name){
                $mas['vendor_name'] = $model->receiveVendor->vendor_name;
            }

            $this->search->index([
                'index' => 'elastic_order',
                'type' => 'order',
                'id' => $model->id,
                'body' => $mas,
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("nDone!");
    }
}
