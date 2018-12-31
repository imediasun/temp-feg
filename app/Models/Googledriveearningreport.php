<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use App\Models\location;

class googledriveearningreport extends Sximo  {
	
	protected $table = 'google_drive_earning_reports';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                        'google_file_id',
                        'file_name',
                        'web_view_link',
                        'icon_link',
                        'modified_time',
                        'created_time',
                        'mime_type',
                        'parent_id',
                        'location_name',
                        'loc_id',
                        'path',
                    ];

	public static function querySelect(  ){
		
		return "SELECT google_drive_earning_reports.*, modified_time as date_start, modified_time as date_end,
      google_drive_earning_reports.loc_id as location_parent_folder_name FROM google_drive_earning_reports";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE google_drive_earning_reports.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public function createOrUpdateFile($file, location $location, $path)
    {

        $saved = $this->updateOrcreate(['google_file_id' => $file->id],
            [
                'google_file_id' => $file->id,
                'file_name' => $file->name,
                'web_view_link' => $file->webViewLink,
                'icon_link' => $file->iconLink,
                'modified_time' => $file->modifiedTime,
                'created_time' =>$file->createdTime,
                'mime_type' =>$file->mimeType,
                'parent_id' =>$file->parents[0],
                'location_name' => $location->location_name_short,
                'loc_id' => $location->id,
                'path'=>$path,
            ]);
        return $saved;
    }

    public static function getRows($args, $cond = null) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'customSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));
        $orderConditional1 = '';
        if (!empty($customSorts)) {
            $customOrderConditionals = [];
            foreach($customSorts as $customSort => $customSortType) {
                $customSortItem = '`'.$customSort.'` '.$customSortType;
                $customOrderConditionals[] = $customSortItem;
            }
            $orderConditional1 = implode(', ', $customOrderConditionals);
            $orderConditional1 = !empty($orderConditional1) ? $orderConditional1.", ":$orderConditional1;
        }

        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$orderConditional1} {$sort} {$order} " : '';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            }
            else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`'.$extraSortItem[0].'`';
                $extraOrderConditionals[] = implode(' ', $extraSortItem);
            }
            $orderConditional .= implode(', ', $extraOrderConditionals);
        }



        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        }
        else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            if($cond != 'only_api_visible')
            {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            else
            {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                if($cond != 'only_api_visible')
                {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }
            else{
                if($cond != 'only_api_visible')
                {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id in($order_type_id)";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }
        if(!empty($active)){//added for location
            $select .= " AND location.active='$active'";
        }
        $select.='AND '.$table.'.loc_id IN ('.\SiteHelpers::getCurrentUserLocationsFromSession().')';
        \Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }
        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        \Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        return $results = array('rows' => $result, 'total' => $total);
    }

    public static function getSearchFilterWithDate($searchQuery){
        $dateStart = '';
        $dateEnd = '';

        if(!empty($searchQuery['date_start'])){
            $dateStart = $searchQuery['date_start']['value'];
        }
        if(!empty($searchQuery['date_end'])){
            $dateEnd = $searchQuery['date_end']['value'];
        }
        $mergeFilters = [];


        if (!empty($dateStart) && empty($dateEnd)){
            $mergeFilters = [
                "modified_time" => [
                    "fieldName" => "modified_time",
                    "operator" => "bigger_equal",
                    "value" => $dateStart
                ]
            ];
        }

        if (!empty($dateEnd) && empty($dateStart)){
            $mergeFilters = [
                "modified_time" => [
                    "fieldName" => "modified_time",
                    "operator" => "smaller_equal",
                    "value" => $dateEnd,
                ]
            ];

        }

        if (!empty($dateStart) && !empty($dateEnd)){
            $mergeFilters = [
                "modified_time" => [
                    "fieldName" => "modified_time",
                    "operator" => "between",
                    "value" => $dateStart,
                    "value2" => $dateEnd,
                ]
            ];
        }
        return $mergeFilters;
    }

}
