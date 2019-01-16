<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use App\Models\location;
use Carbon\Carbon;

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
                        'type',
                        'created_at',
                        'updated_at'
                    ];

	public static function querySelect(  ){
		
		return "SELECT google_drive_earning_reports.* ,  google_drive_earning_reports.modified_time AS date_start,  google_drive_earning_reports.modified_time AS date_end,  location.gd_parent_folder_name 
                FROM google_drive_earning_reports   LEFT JOIN location  ON google_drive_earning_reports.loc_id = location.id";
	}

    public static function queryCountSelect(  ){

        return "SELECT COUNT(google_drive_earning_reports.id) as totalCount,google_drive_earning_reports.id 
                FROM google_drive_earning_reports   LEFT JOIN location  ON google_drive_earning_reports.loc_id = location.id";
    }
    /*
    public static function getRowsCount(){
	     return "SELECT COUNT(google_drive_earning_reports.id) as totalCount FROM google_drive_earning_reports   LEFT JOIN location  ON google_drive_earning_reports.loc_id = location.id";
     }
    */


	public static function queryWhere(  ){
        $currentLocationIds = \SiteHelpers::getCurrentUserLocationsFromSession();
		return "  WHERE google_drive_earning_reports.id IS NOT NULL AND loc_id IN ($currentLocationIds)";

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
                'type' => $file->period,
                'path'=>$path,
            ]);
        if($saved->wasRecentlyCreated){
            $saved->created_at = Carbon::now();
            $saved->updated_at = Carbon::now();
        }
        else
        {
            $saved->updated_at = Carbon::now();
        }
        $saved->save();
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

         $orderConditions = $orderConditional;



        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();
        /*

        */
        //$createdFlag = false;

        if ($cond != null) {
            $orderConditional = self::queryWhere($cond);
        }
        else {
            $orderConditional = self::queryWhere();
        }

        $countSelect = self::queryCountSelect();
        $countQuery = $countSelect . " {$orderConditional} {$params} " . self::queryGroup();
        $counter_select =\DB::select($countQuery);
        \Log::info("Total Query : $countQuery");
        $total = $counter_select[0]->totalCount;
        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }


        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';

        $selectQuery = "$select $orderConditional $params ".self::queryGroup()." $orderConditions $limitConditional";
        \Log::info("Query :  $selectQuery");
        $result = \DB::select($selectQuery);

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
