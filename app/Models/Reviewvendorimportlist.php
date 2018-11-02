<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class reviewvendorimportlist extends Sximo  {
	
	protected $table = 'vendor_import_products';
	protected $primaryKey = 'id';
    protected $editableGridColumns = [
        'ticket_value'=>['field'=>'text'],
        'retail_price'=>['field'=>'text'],
        'prod_type_id'=>['field'=>'select','source'=>'order_type'],
        'prod_sub_type_id'=>['field'=>'select','source'=>'product_type'],
        'expence_category'=>['field'=>'select','source'=>'expense_category_mapping'],
        'is_reserved'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'allow_negative_reserve_qty'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'inactive'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'in_development'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'hot_item'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
        'exclude_export'=>['field'=>'select','source'=>'custom','type'=>'boolean'],
    ];

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT vendor_import_products.* FROM vendor_import_products  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE vendor_import_products.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    /**
     * @param bool $onlyColumnName
     * @return array
     */
	public function editableGridColumns($onlyColumnName = false){
	    return $onlyColumnName == true ? array_keys($this->editableGridColumns):$this->editableGridColumns;
    }
    public function getEditableColumns(){
        $columns = $this->editableGridColumns();
        foreach ($columns as $column){
            if($column['field'] == 'select'){

            }
        }
        return $columns;
    }

    public function setFieldOptions($field = []){
        if ($field['source'] == 'custom' && $field['type'] == 'boolean'){
            $field['field']['options'] = [
                [ 'value'=>0, 'display_text'=>'No'],
                [ 'value'=>1, 'display_text'=>'Yes']
            ];
        }else{
            $field['field']['options'] = [
                [ 'value'=>0, 'display_text'=>'No'],
                [ 'value'=>1, 'display_text'=>'Yes']
            ];
        }
    }

}
