<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\FEG\System\FEGSystemHelper;

class newlocationsetup extends Sximo  {
	
	protected $table = 'new_location_setups';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT
  new_location_setups.*,
  location.id            AS FEG_ID,location.store_id,
  IF(location.active = 0,'Closed','Open') AS locationStatus
FROM new_location_setups
  INNER JOIN location
    ON location.id = new_location_setups.location_id  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE new_location_setups.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
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
        $select.=' AND '.$table.'.location_id IN ('.\SiteHelpers::getCurrentUserLocationsFromSession().')';
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

    /**
     * @param int $locationSetupId
     * @param array $message[element5DigitalMessage,sacoa,embed,internal_team]
     */
    public function sendNotificationByEmail($locationSetupId = 0, $message =[], $locname)
    {
        $newLocationSetup = self::find($locationSetupId);
        if ($newLocationSetup) {
            $location = location::select('*')
                ->where('location.id', $newLocationSetup->location_id)
                ->join('report_locations', 'report_locations.location_id', '=', 'location.id')->orderBy('date_last_played', 'desc')->first();
            if ($location) {

                $isTest = env('APP_ENV', 'development') !== 'production' ? true : false;
                $from = \Session::get('eid');

                $this->sendNotificationToElement5Digital($newLocationSetup->location_id,$message['element5Digital'],$from,$isTest, $locname);
                $this->sendNotificationInternalTeam($newLocationSetup->location_id,$message['internal_team'],$from,$isTest, $locname);

                if($location->debit_type_id >= 1){
                    $locationType = self::getLocationType($location->debit_type_id);
                    $this->sendNotificationByLocationType($newLocationSetup->location_id,$locationType,$message[$locationType],$from,$isTest);

                }

            }
        }
    }

    /**
     * @param $locationId
     * @param $message
     * @param $from
     * @param $isTest
     */
    public function sendNotificationToElement5Digital($locationId,$message,$from,$isTest, $locname){
        $configName = 'Notify to install the sync application on new server [Element5Digital]';
        $receipts = FEGSystemHelper::getSystemEmailRecipients($configName, null, $isTest);

        $subject = "New Location Server ($locationId  $locname)";

        if (!empty($receipts)) {
            FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
                'subject' => $subject,
                'message' => $message,
                'isTest' => $isTest,
                'configName' => $configName,
                'from' => $from,
                'replyTo' => $from,
            )));
        }
    }

    public function sendNotificationByLocationType($locationId,$locationType, $message,$from,$isTest){

        $configName = 'Notify to install the sync application on new server ['.$locationType.']';
        $receipts = FEGSystemHelper::getSystemEmailRecipients($configName, null, $isTest);

        $subject = 'New Location Server ['.$locationId.'] '.ucfirst($locationType);

        if (!empty($receipts)) {
            FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
                'subject' => $subject,
                'message' => $message,
                'isTest' => $isTest,
                'configName' => $configName,
                'from' => $from,
                'replyTo' => $from,
            )));
        }

    }

    public function sendNotificationInternalTeam($locationId, $message,$from,$isTest, $locname){

        $configName = 'Notify to install the sync application on new server [Internal Team]';
        $receipts = FEGSystemHelper::getSystemEmailRecipients($configName, null, $isTest);
        $subject = "New Location Server ($locationId  $locname)";

        if (!empty($receipts)) {
            FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
                'subject' => $subject,
                'message' => $message,
                'isTest' => $isTest,
                'configName' => $configName,
                'from' => $from,
                'replyTo' => $from,
            )));
        }

    }

    public static function getLocationType($debitTypeId){
         $debitTypes = array("1" => "sacoa", "2" => "embed");
        return isset($debitTypes[$debitTypeId]) ? $debitTypes[$debitTypeId] : null;
    }
    public static function getUserAssignedLocation($extra = []){
        $locations = \SiteHelpers::getLocationDetails(\Session::get('uid'), false, $extra, true);
        return $locations;
    }

}
