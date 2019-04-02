<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productsubtype extends Sximo  {
	
	protected $table = 'product_type';
	protected $primaryKey = 'id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT product_type.*, order_type.order_type FROM product_type 
                  INNER JOIN order_type ON (product_type.request_type_id = order_type.id) ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE product_type.id IS NOT NULL AND product_type.deleted_at IS NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subType(){
        return $this->belongsTo(self::class, 'request_type_id', 'id');
    }
}
