<?php namespace App\Models\Sximo;

use App\Models\Sximo;
use Illuminate\Database\Eloquent\Model;

class Module extends Sximo {

	protected $table 		= 'tb_module';
	protected $primaryKey 	= 'module_id';

	public function __construct() {
		parent::__construct();	
	} 

    public static function id2name($id) {
        $minutes = 240;
        $cacheKey = md5("module-id2name-$id");
        return \Cache::remember($cacheKey, $minutes, function () use ($id) { 
            return self::where('module_id', $id)->pluck('module_name');
        });        
        
    }
    public static function name2id($name) {
        $minutes = 240;
        $cacheKey = md5("module-name2id-$name");
        return \Cache::remember($cacheKey, $minutes, function () use ($name) { 
            return self::where('module_name', $name)->pluck('module_id');
        });
    }
    public static function name2title($name) {
        return self::where('module_name', $name)->pluck('module_title');
    }
    public static function id2title($id) {
        return self::where('module_id', $id)->pluck('module_title');
    }
}