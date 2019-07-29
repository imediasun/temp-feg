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
                    ],
                    "notes"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    "location_name"=> [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'id' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                    'user' => [
                        'type' => 'text',
                        'analyzer' => "ngram_analyzer_with_filter",
                    ],
                ]
            ]
        ];


        if ($this->search->indices()->exists(['index'=>'elastic_managefegrequeststore'])) {
            $this->search->indices()->delete(['index'=>'elastic_managefegrequeststore']);
        }

        $this->search->indices()->create($param);
        $this->search->indices()->putMapping($mapping);


        //////////////////////////

$orders=Managefegrequeststore::with('product')->with('receiveLocation')->with('orderedBy')->get();
    foreach ($orders as $model) {

        if($model->request_date=='0000-00-00'){
            $model->request_date='1978-01-01';
        }
            $mas = $model->toSearchArray();
            //dump(!null==($model->location));
        if($model->product){
            $vendor=\App\Models\Vendor::where('id',$model->product->vendor_id)->first();
        }

        if($model->product){
            $mas['sku'] =$model->product->sku;
            $mas['product_type'] =\App\Models\OrderType::where('id',$model->product->prod_type_id)->first()->order_type;
            $product_subtype=\App\Models\ProductType::where('id',$model->product->prod_sub_type_id)->first();
            if($product_subtype){
            $mas['product_subtype'] =$product_subtype->type_description;
            //dump( $mas['product_subtype']);
            }
           //dump( $mas['product_type']);
        }

            if(isset($vendor) && !null==($vendor)){
                //dump($vendor->vendor_name);
                $mas['vendor_name'] = $vendor->vendor_name;
            }
        if(!null==($model->product)){
            //dump($model->product->vendor_description);
            $mas['item_name'] = $model->product->vendor_description;
        }


        if(/*isset($model->receiveLocation) && null!=($model->receiveLocation) &&*/ isset($model->receiveLocation->location_name) && null!=$model->receiveLocation->location_name){
            $mas['location_name'] = $model->receiveLocation->location_name;
            //dump($mas['location_name']);
        }

        if( isset($model->orderedBy->first_name) && null!=$model->orderedBy->first_name){
            $mas['user'] = $model->orderedBy->first_name.".".$model->orderedBy->last_name;
            dump($mas['user']);
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
