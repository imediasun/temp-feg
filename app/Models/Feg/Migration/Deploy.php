<?php namespace App\Models\Feg\Migration;

use DB;
use PDO;
use Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sximo;
use \Illuminate\Database\QueryException;
use \Exception;
use FEGFormat;
use Input;

class Deploy extends Sximo  {

    protected $connection = 'migration';
	protected $table = 'databases';
    
	public function __construct() {
		parent::__construct();
        $alias = "database.connections.".$this->connection;
        if (Config::has($alias)) {
            return;
        }
        Config::set($alias,
                [
                    'host'      => env('DB_HOST', 'localhost'),
                    'username'  => env('DB_USERNAME', 'root'),
                    'password'  => env('DB_PASSWORD', 'root'),
                    'database'  => env('DB_PREFIX', 'fegllc_').'migration_helper',
                    'driver'  => 'mysql',
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                    'options'   => array(
                        PDO::ATTR_TIMEOUT => 120,
                        PDO::ATTR_PERSISTENT => true,
                    )
                ]);
	}
	public function __destruct() {
        DB::purge($this->connection);
        //parent::__destruct();
	}

    public static function _DeleteData($request, $table) {
        $obj = new self;
        $connection = $obj->connection;
        $ids = $request['deletedIds'];
        $table = $request['table'];
        $success = DB::connection($connection)->table($table)
                ->whereIn('id', explode(',', $ids))->delete();
        return $success;
    }
    public static function _SaveData($request, $table) {
        $request = Input::all();
        $obj = new self;
        $connection = $obj->connection;
        $data = DB::connection($connection)->table($table)
                ->whereIn('id', explode(',', $ids));
        return $data;
    }
    public static function _getData($table) {
        $obj = new self;
        $connection = $obj->connection;
        //DB::connection($connection)->setFetchMode(PDO::FETCH_ASSOC);
        $data = DB::connection($connection)->table($table)->orderBy('id', 'DESC')->get();
        //DB::connection($connection)->setFetchMode(PDO::FETCH_CLASS);
        return $data;
    }

    public static function getDatabaseList() {
        $list = self::select('alias', 'host', 'database', 'username', 'password')
                ->get()
                ->toArray();
        $me = new self;
        $data = [];
        foreach($list as $item) {
            $alias = $item['alias'];
            unset($item['alias']);
            $data[$alias] = array_merge(
                [
                    'driver'    => 'mysql',
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                    'options'   => array(
                        PDO::ATTR_TIMEOUT => 120,
                        PDO::ATTR_PERSISTENT => true,
                    )
                ], $item);            
        }

        return $data;
    }


}
