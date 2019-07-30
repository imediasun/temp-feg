<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Search\Searchable;
use App\Repositories\Managenewgraphicrequests\ManagenewgraphicrequestsRepository;
use App\Repositories\Managenewgraphicrequests\ElasticsearchManagenewgraphicrequestsRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class Newgraphicrequestsstatus extends Sximo
{
    use Searchable;
    protected $table = 'new_graphics_request_status';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }



}
