<?php
namespace App\Library;
class ReportHelpers
{

    public static function getLocationRanksQuery($dateStart, $dateEnd, $location = "", $debit = "", $sortby = "pgpd_avg", $order = "")
    {
        
        $dateEnd_ymd = self::dateify($dateEnd);
        $daydiff = self::daydiff($dateStart, $dateEnd);
        
        
        //                    sum(IFNULL(E.games_total_std_plays, 0)) AS total_plays, 
        $Q = "SELECT 
                    L.id, 
                    L.location_name_short as location_name, 
                    L.debit_type_id,
                    D.company as debit_system,
                    '$dateStart' as date_start,
                    '$dateEnd_ymd' as date_end,
                    sum(IFNULL(E.games_revenue, 0)) AS location_total,
                    sum(E.report_status) as days_reported_count,
                    '' as days_reported,
                    '' as days_reported_text,
                    $daydiff as days_count,
                    max(IFNULL(E.games_count, 0)) as game_count,
                    sum(IFNULL(E.games_revenue, 0))/(sum(E.report_status)*max(IFNULL(E.games_count, 0))) as pgpd_avg

                FROM report_locations E
                LEFT JOIN location L ON L.id = E.location_id
                LEFT JOIN debit_type D ON D.id = L.debit_type_id
                WHERE 
                E.record_status = 1 AND
                E.date_played >= '$dateStart' and E.date_played <= '$dateEnd' ";

        if (!empty($location)) {
            $Q .= " AND E.location_id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND E.debit_type_id IN ($debit)";
        }
        
        // GROUP BY
        $Q .= " GROUP BY E.location_id ";
        
        
        // ORDER BY
        $sortbys = array(
            "days_reported" => "days_reported_count", 
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = " ORDER BY $sortby $order";
        $Q .= $sortbyQuery;        

        return $Q;
    }
    public static function getLocationRanksQueryERPDB($dateStart, $dateEnd, $location, $debit, $orderField = "pgpd_avg", $order = "")
    {
    }
    public static function getLocationRanksQueryTEMPDB($dateStart, $dateEnd, $location, $debit, $orderField = "pgpd_avg", $order = "")
    {
    }    

    public static function getDumpSummaryQuery()
    {
    }
    public static function getLocationNotRespondingQuery()
    {
    }
    public static function getLocationsRespondingQuery()
    {
    }

    public static function getReadersWihtMissingAssetIdsQuery()
    {
    }
    public static function getAssetIdsWithMissingReadersQuery()
    {
    }
    public static function getMissingAssetIdMissingReadersQuery()
    {
    }
    public static function getGamesNotOnDebitCardQuery()
    {
    }
    public static function getExcludedReadersQuery()
    {
    }
    public static function getAdjustmentsQuery()
    {
    }
    public static function getPotentialOverReportingQuery()
    {
    }
    public static function getGamesNotPlayedQuery()
    {
    }
    public static function getGamesPlayedQuery()
    {
    }    
    public static function getGameRankQuery()
    {
    }    
    public static function getMerchThrowsSimpleReportQuery()
    {
    }
    public static function getMerchThrowsDetailedReportQuery()
    {
    }
    public static function getProductUsageQuery()
    {
    }
    public static function getProductInDevelopmentQuery()
    {
    }
    public static function getMerchExpenseReportQuery()
    {
    }
    
    /**
     * Get submitted search filter values in an associative array
     * @return Array 
     */
    public static function getSearchFilters($requiredFilters = array()) {
        $receivedFilters = array();
        $finalFilters = array();
        if (isset($_GET['search'])) {
            $filters_raw = trim($_GET['search'], "|");
            $filters = explode("|", $filters_raw);

            foreach($filters as $filter) {
                $columnFilter = explode(":", $filter);
                if (isset($columnFilter) && isset($columnFilter[0]) && isset($columnFilter[2])) {
                    $receivedFilters[$columnFilter[0]] = $columnFilter[2];
                }
            }
        }
                
        if (empty($requiredFilters)) {
            $finalFilters = $receivedFilters;
        }
        else {
            foreach($requiredFilters as $fieldName => $variableName) {
                if (empty($variableName)) {
                    $variableName = $fieldName;
                }
                if (isset($receivedFilters[$fieldName])) {
                    $finalFilters[$variableName] = $receivedFilters[$fieldName];
                }
                else {
                    $finalFilters[$variableName] = '';
                }
            }
        }
        
        return $finalFilters;
    }
    
    /** 
     * Easy date generator. Pass an Integer as second parameter to offset days.
     * 
     * @param String $date     A valid date string. Defaults to current server date
     * @param Number $deltaDays    Number (Integer) of days to offset before generating new date. Can be negative.
     * @return String   Date in "yyyy-mm-dd" format
     * 
     * @example datify("", -1) will generate yesterday's date
     */
    public static function dateify($date = "", $deltaDays = 0) {
        $newDate = date("Y-m-d", strtotime((empty($date)? "now": $date). (" $deltaDays day")));
        return $newDate;        
    }
    
    /**
     * Fix a date range. Defaults to current date if nothing is proviced as parameter.
     * Swaps values if end date is less than start date. 
     * Adds 23:59:59 time to end date if last parameter is set to true.
     * 
     * @note: This function accepts variable referances as parameters and modifies actual variables
     * 
     * @param String $startDate   A valid date string
     * @param String $endDate   A valid date string
     * @param Boolean $includeEndInRange   Whether full day of end date is inclusive in the range
     * @return Array 
     */
    public static function dateRangeFix(&$startDate = "", &$endDate = "", $includeEndInRange = true) {
        $newStartDate = trim($startDate);
        $newEndDate = trim($endDate);
        $yesterday = self::dateify("", -1);
        if (empty($newStartDate)) {
            if (empty($newEndDate)) {
                $newStartDate = $yesterday;
            }
            else {
                $newStartDate = $newEndDate;
            }
        }
        $newStartDate = self::dateify(trim($newStartDate));
        
        if (empty($newEndDate)) {
            if (empty($newStartDate)) {
                $newEndDate = $yesterday;
            }
            else {
                $newEndDate = $newStartDate;
            }
        }
        $newEndDate = self::dateify(trim($newEndDate));
        
        $startDatestamp = strtotime($newStartDate);
        $endDatestamp = strtotime($newEndDate);
        if ($endDatestamp < $startDatestamp) {
            $tempDate = $newEndDate;
            $newEndDate = $newStartDate;
            $newStartDate = $tempDate;            
        }
        
        $startDate = $newStartDate;
        $endDate = $newEndDate;
        
        if ($includeEndInRange) {
            $endDate = $endDate . ' 23:59:59';
        }
        
        
        return array(
            "start" => $startDate,
            "end" => $endDate,
        );                
    }
    
    /**
     * Counts the number of days between 2 dates. The end date is included 
     * in the count if last paramter is true
     * @param String $date1   A valid date string
     * @param String $date2   A valid date string
     * @param Boolean $inclusive  Whether end data is included in the count
     * @return number
     */
    public static function daydiff($date1, $date2, $inclusive = true) {
        $datetime1 = strtotime($date1);
        $datetime2 = strtotime($date2);
        $timediff = abs($datetime2 - $datetime1);
        $daydiff = floor($timediff/(60*60*24));
        if ($inclusive) {
            $daydiff++;
        }
        return $daydiff;        
    }   
    /**
     * Get human readable dates
     * @param type $date
     * @param type $format
     * @return type
     */
    public static function humanifydate($date, $format = "l, F d Y") {
        $ret = "";
        if (!empty($date)) {
            $ret = date($format, strtotime($date));
        }
        return $ret;        
    }      
    
    /**
     * 
     * @param type $dateStart
     * @param type $dateEnd
     * @param type $format
     * @return type
     */
    public static function humanifyDateRangeMessage($dateStart, $dateEnd = "", $format = "l, F d Y") {
        $ret = "";
        $dateStartHuman = self::humanifydate($dateStart, $format);
        $dateEndHuman = self::humanifydate($dateEnd, $format);
        if (!empty($dateStartHuman)) {
            if (!empty($dateEnd)) {
                $dateEndYMD = self::dateify($dateEnd);
            }            
            if ($dateEndYMD == $dateStart) {
                $dateEndHuman = "";
            }            
            if (empty($dateEndHuman)) {
                $ret = " for $dateStartHuman";
            }
            else {
                $ret = " between $dateStartHuman and $dateEndHuman";
            }
        }
        return $ret;        
    }       
    
    private function sanitizeValue($val, $default = "") {
        $ret = @$val;
        if (empty($ret)) {
            $ret = $default;
        }
        return $ret;
    }
    private function sanitizeNumber($val, $default = 0) {
        $ret = @$val;
        if (!is_numeric($ret)) {
            $ret = $default;
        }        
        return $ret;
    }
    private function sanitizeString($val, $default = "") {
        $ret = @$val;
        if (!is_string($ret)) {
            $ret = $default;
        }           
        return $ret;
    }
    private function sanitizeArray($val, $default = array()) {
        $ret = @$val;
        if (!is_array($ret)) {
            $ret = $default;
        }        
        return $ret;
    }
    private function sanitizeBoolean($val, $default = FALSE) {
        $ret = @$val;
        if (!is_bool($ret)) {
            $ret = $default;
        }
        return $ret;
    }
    private function sanitizeBoolean01($val, $default = 0) {
        $ret = @$val;
        if ($ret !== 1 && $ret !== 0) {
            $ret = $default;
        }        
        return $ret;
    }
    
    // Under Development
    public static function getMenuAccessDetails($parent = 0, $position = 'top', $active = '1')
    {
        $group_sql = " AND tb_menu_access.group_id ='" . Session::get('gid') . "' ";
        $active = ($active == 'all' ? "" : "AND active ='1' ");
        $Q = DB::select("
		SELECT 
			tb_menu.*
		FROM tb_menu WHERE parent_id ='" . $parent . "' " . $active . " AND position ='{$position}'
		GROUP BY tb_menu.menu_id ORDER BY ordering			
		");
        return $Q;
    }    
}
