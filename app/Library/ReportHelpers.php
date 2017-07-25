<?php
namespace App\Library;

use App\Library\MyLog;

class ReportHelpers
{
    public static function getLocationRanksQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $sortby = "pgpd_avg", $order = "") {
        
        $dateEnd_ymd = self::dateify($dateEnd);
        $daydiff = self::daydiff($dateStart, $dateEnd);
        
        
        //                    sum(IFNULL(E.games_total_std_plays, 0)) AS total_plays, 
        $Q = "SELECT 
                    E.location_id as id, 
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
                E.date_played >= L.date_opened AND 
                E.date_played >= '$dateStart' and E.date_played <= '$dateEnd' ";

        if (!empty($location)) {
            $Q .= " AND E.location_id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit)";
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
        $sortbyQuery = self::orderify($sortby, $order);        
        $Q .= $sortbyQuery;        

        $L = new MyLog("location-rank.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;
    }
    
    
    public static function getLocationNotReportingQuery($dateStart, $dateEnd, $location, 
            $debit, $sortby = "not_reporting_date", $order = "") {
        $dateEnd_ymd = self::dateify($dateEnd);
        
        $Q = "SELECT
                    E.location_id as id, 
                    L.location_name_short as location_name, 
                    E.date_played as not_reporting_date,
                    E.date_last_played as date_last_reported,
                    DATEDIFF(E.date_played, E.date_last_played) as days_not_reporting,
                    A.notes as not_reporting_status,
                    L.debit_type_id,
                    D.company as debit_system,
                    '$dateStart' as date_start,
                    '$dateEnd_ymd' as date_end,
                    A.status as adjustment_status
                   
                ";
        
        $Q .= self::_getLocationNotReportingQuery($dateStart, $dateEnd, $location, $debit);  
                
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }      
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        
        
        $L = new MyLog("location-not-reporting.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;        
    }
    public static function _getLocationNotReportingQuery($dateStart, $dateEnd, $location, $debit) {
        $Q = "
                FROM report_locations E
                LEFT JOIN location L ON L.id = E.location_id
                LEFT JOIN debit_type D ON D.id = L.debit_type_id
                LEFT JOIN game_earnings_transfer_adjustments A ON A.loc_id = E.location_id AND A.date_start = E.date_played
                WHERE 
                E.record_status = 1 AND
                E.report_status = 0 AND
                E.date_played >= L.date_opened AND 
                E.date_played >= '$dateStart' and E.date_played <= '$dateEnd' ";

        if (!empty($location)) {
            $Q .= " AND E.location_id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit)";
        }
        
        // GROUP BY
        //$Q .= " ";
        return $Q;
    }
    public static function getLocationNotReportingCount($dateStart, $dateEnd, $location, $debit) {        
        $Q = "SELECT count(*) as `count` ";
        $Q .= self::_getLocationNotReportingQuery($dateStart, $dateEnd, $location, $debit);        
        $count = self::getCountFromQuery($Q);
        return $count;     
    }
    
    
    public static function getClosedLocationsCount($dateStart, $dateEnd, $location, $debit) {
        $Q = "SELECT count(*) as `count` ";
        $Q .= self::_getClosedLocationsQuery($dateStart, $dateEnd, $location, $debit);        
        $count = self::getCountFromQuery($Q);
        return $count;
    }
    public static function _getClosedLocationsQuery($dateStart, $dateEnd, $location, $debit) {
        $Q =  "        FROM game_earnings_transfer_adjustments A
                LEFT JOIN location L ON L.id = A.loc_id
                LEFT JOIN debit_type D ON D.id = L.debit_type_id                
                WHERE 
                A.status = 0 AND
                A.notes = 'CLOSED' AND
                A.date_start >= L.date_opened AND 
                A.date_start >= '$dateStart' and A.date_start <= '$dateEnd' ";

        if (!empty($location)) {
            $Q .= " AND A.loc_id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit)";
        }        
        // GROUP BY
        //$Q .= " ";        
        
        return $Q;
    }
    public static function getClosedLocationsQuery($dateStart, $dateEnd, 
            $location, $debit, $sortby = "closed_date", $order = "") {
        $dateEnd_ymd = self::dateify($dateEnd);
        
        $Q = "SELECT 
                    A.loc_id as id, 
                    L.location_name_short as location_name, 
                    L.debit_type_id,
                    D.company as debit_system,
                    '$dateStart' as date_start,
                    '$dateEnd_ymd' as date_end,
                    A.date_start closed_date
                ";
        
        $Q .= self::_getClosedLocationsQuery($dateStart, $dateEnd, $location, $debit);        
                
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("location-closed.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;         
    }


    public static function getReadersMissingAssetIdQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "", $sortby = "date_start", $order = "") {
        
        $Q = "SELECT E.id,
            E.date_start,
            E.date_end,
            E.reader_id, 
            E.loc_id AS location_id,
            L.location_name_short,
            E.debit_type_id,
            D.company as debit_system,
            E.loc_game_title, 
            SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END) as game_total ";
        
        $Q .= self::_getReadersMissingAssetIdQuery($dateStart, $dateEnd, $location, $debit, $reader);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("readers-missing-assetid.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;                  
    }
    public static function _getReadersMissingAssetIdQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "") {  
        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        }
        
        $Q = "
            FROM game_earnings E
            LEFT JOIN location L ON L.id = E.loc_id
            LEFT JOIN debit_type D ON D.id = E.debit_type_id
            
            WHERE (E.game_id = 0 OR E.game_id IS NULL) ";
                     
        if (!empty($reader)) {
            $Q .= " AND E.reader_id LIKE '%$reader%' ";
        }
        if (!empty($dateStart)) {
            $Q .= " AND E.date_start >= '$dateStart' ";
        }
        
        if (!empty($dateEnd)) {
            $Q .= " AND E.date_start <= '$dateEnd 23:59:59' ";
        }
        
        if (!empty($location)) {
            $Q .= " AND E.loc_id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND E.debit_type_id IN ($debit) ";
        } 
        
        // GROUP BY
        $Q .= " GROUP BY E.date_start, E.reader_id, E.loc_id ";
        
        return $Q;
    }
    public static function getReadersMissingAssetIdCount($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "") {
        $Q = "SELECT count(*) as `count` FROM (SELECT E.date_start ";
        $Q .= self::_getReadersMissingAssetIdQuery($dateStart, $dateEnd, $location, $debit, $reader); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;        
    }


    public static function getMissingReadersQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $asset = "", $sortby = "date_start", $order = "") {
        
        $Q = "SELECT E.id,
            date_format(E.date_start, '%Y-%m-%d') as date_start,
            E.date_end,
            E.game_id, 
            IF(E.debit_type_id = 1 , SUBSTRING(E.reader_id, 7), reader_id) AS reader_id, 
            E.loc_id AS location_id,
            L.location_name_short,
            E.debit_type_id,
            D.company as debit_system,
            E.loc_game_title, 
            SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END) as game_total ";
        
        $Q .= self::_getMissingReadersQuery($dateStart, $dateEnd, $location, $debit, $asset);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("missing-readers.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;                  
    }
    public static function _getMissingReadersQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $asset = "") {  
        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        }
        
        $Q = "
            FROM game_earnings E
            LEFT JOIN location L ON L.id = E.loc_id
            LEFT JOIN debit_type D ON D.id = E.debit_type_id            
            WHERE (
                E.reader_id = '0' OR 
                E.reader_id IS NULL OR 
                E.reader_id = '' 
                OR (E.debit_type_id = 1 AND SUBSTRING(E.reader_id, 7)='0') 
                OR E.reader_id REGEXP '[^a-z_0-9]'
            ) ";
                     
        if (!empty($asset)) {
            $Q .= " AND E.game_id = $asset ";
        }
        if (!empty($dateStart)) {
            $Q .= " AND E.date_start >= '$dateStart' ";
        }
        
        if (!empty($dateEnd)) {
            $Q .= " AND E.date_start <= '$dateEnd 23:59:59' ";
        }
        
        if (!empty($location)) {
            $Q .= " AND E.loc_id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND E.debit_type_id IN ($debit) ";
        } 
        
        // GROUP BY
        $Q .= " GROUP BY E.date_start, E.loc_id, E.game_id, E.reader_id ";
        
        return $Q;
    }
    public static function getMissingReadersCount($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "") {
        $Q = "SELECT count(*) as `count` FROM (SELECT E.date_start ";
        $Q .= self::_getMissingReadersQuery($dateStart, $dateEnd, $location, $debit, $reader); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;        
    }


    public static function getUnknownAssetIdQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "", $sortby = "date_start", $order = "") {
        
        $Q = "SELECT E.id,
            date_format(E.date_start, '%Y-%m-%d') as date_start,
            E.date_end,
            E.game_id, 
            IF(E.debit_type_id = 1 , SUBSTRING(E.reader_id, 7), reader_id) AS reader_id, 
            E.loc_id AS location_id,
            L.location_name_short,
            E.debit_type_id,
            D.company as debit_system,
            E.loc_game_title,             
            SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END) as game_total ";
        
        $Q .= self::_getUnknownAssetIdQuery($dateStart, $dateEnd, $location, $debit, $reader);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("unknown-assetid.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;                  
    }
    public static function _getUnknownAssetIdQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "") {  
        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        }
        
        $Q = "
            FROM game_earnings E
            LEFT JOIN location L ON L.id = E.loc_id
            LEFT JOIN debit_type D ON D.id = E.debit_type_id
            WHERE 
            E.game_id NOT IN (SELECT id from game WHERE sold <> 1)
            AND E.game_id != 0 AND game_id IS NOT NULL
            ";
                     
        if (!empty($reader)) {
            $Q .= " AND E.reader_id LIKE '%$reader%' ";
        }
        if (!empty($dateStart)) {
            $Q .= " AND E.date_start >= '$dateStart' ";
        }
        
        if (!empty($dateEnd)) {
            $Q .= " AND E.date_start <= '$dateEnd 23:59:59' ";
        }
        
        if (!empty($location)) {
            $Q .= " AND E.loc_id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND E.debit_type_id IN ($debit) ";
        } 
        
        // GROUP BY
        $Q .= " GROUP BY E.date_start, E.loc_id, E.game_id, E.reader_id ";
        
        return $Q;
    }
    public static function getUnknownAssetIdCount($dateStart, $dateEnd, 
            $location = "", $debit = "", $reader = "") {
        $Q = "SELECT count(*) as `count` FROM (SELECT E.date_start ";
        $Q .= self::_getReadersMissingAssetIdQuery($dateStart, $dateEnd, $location, $debit, $reader); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;        
    }

    
    public static function getPotentialOverReportingErrorQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $gameType = "", $gameCat = "all", 
            $onTest = "", $gameId = "", $sortby = "date_start", $order = "") {
        extract(self::getGameCategoryDetails($gameCat));        
        $Q = "SELECT E.id,
                E.location_id,
                L.location_name_short as location_name,
                L.debit_type_id,
                D.company as debit_system,
                E.game_id,
                E.game_title_id,
                T.game_title as game_name,
                if(E.game_on_test = 1, 'Yes', 'No') as game_on_test,
                E.game_not_debit,
                Y.id as game_type_id,
                Y.game_type,
                '$gameCat' AS game_cat_id,
                '$game_category' AS game_category,                
                SUM(IFNULL(E.game_revenue,0)) AS game_total,
                E.date_played as date_start,
                E.date_played as date_end
                ";
        
        $Q .= self::_getPotentialOverReportingErrorQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest, $gameId);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("games-potential-overreporting.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;         
    }
    public static function _getPotentialOverReportingErrorQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $gameType = "", $gameCat = "all", 
            $onTest = "", $gameId = "") {
        extract(self::getGameCategoryDetails($gameCat));
        $gameTypeIds = self::mergeGameTypeAndCategories($gameType, $game_category_type);        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        }        
        $Q = "
            FROM report_game_plays E
   LEFT JOIN game G ON G.id = E.game_id
   LEFT JOIN location L ON L.id = E.location_id
   LEFT JOIN debit_type D ON D.id = L.debit_type_id   
   LEFT JOIN game_title T ON T.id = G.game_title_id
   LEFT JOIN game_type Y ON Y.id = T.game_type_id
	   WHERE E.game_id <> 0 AND E.record_status = 1 AND E.report_status = 1 ";
                     
        if (!empty($gameId)) {
            $Q .= " AND E.game_id IN ($gameId) ";
        }
        if (!empty($dateStart)) {
            $Q .= " AND E.date_played >= '$dateStart' ";
        }        
        if (!empty($dateEnd)) {
            $Q .= " AND E.date_played <= '$dateEnd 23:59:59' ";
        }        
        if (!empty($location)) {
            $Q .= " AND E.location_id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit) ";
        }
        if (!empty($onTest)) {
            if ($onTest == "notest") {
                $Q .= " AND E.game_on_test IN (0)";
            }
            else if($onTest == "all")
            {

            }
            else {
                $Q .= " AND E.game_on_test IN (1)";
            }            
        }
        if (!empty($gameTypeIds)) {
            $Q .= " AND Y.id IN ($gameTypeIds)";
        }
        
        // GROUP BY
        $Q .= " GROUP BY E.date_played, E.location_id, E.game_id HAVING SUM(IFNULL(E.game_revenue,0)) > 4000";
        
        return $Q;        
        
    }
    public static function getPotentialOverReportingErrorCount($dateStart, $dateEnd, 
            $location = "", $debit = "", $gameType = "", $gameCat = "all", 
            $onTest = "", $gameId = "") {
        $Q = "SELECT count(*) as `count` FROM (SELECT E.date_played ";
        $Q .= self::_getPotentialOverReportingErrorQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest, $gameId); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;          
    }
  
    
    public static function getGameRankCount($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "") {
        $Q = "SELECT count(*) as `count` FROM (SELECT E.game_title_id ";
        $Q .= self::_getGameRankQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;        
    }
    public static function getGameRankQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $sortby = "game_average", $order = "") {
        extract(self::getGameCategoryDetails($gameCat));
        //sum(E.game_std_plays) AS total_plays,
        $Q = "SELECT 
            E.id, 
            GT.game_title as game_name, 
            E.game_title_id, 
            GTY.id as game_type_id,
            GTY.game_type,
            if(E.game_on_test = 1, 'Yes', 'No') as game_on_test,
            L.debit_type_id,
            D.company as debit_system,
            '$gameCat' AS game_cat_id,
            '$game_category' AS game_category,
                
            sum(IFNULL(E.game_revenue,0)) AS game_total,
            sum(IFNULL(E.game_revenue,0)) / if(count(distinct E.game_id)=0, 1, count(distinct E.game_id)) as game_average,
            count(distinct E.game_id) as game_count,
            
            '$dateStart'  as date_start,
            '$dateEnd'  as date_end,
            group_concat(distinct E.game_id SEPARATOR ', ') as game_ids, 
            group_concat(distinct L.id  ORDER BY L.id SEPARATOR ', ') as location_id, 
            group_concat(distinct L.location_name_short ORDER BY L.id SEPARATOR ', ') as location_name ";
        
        $Q .= self::_getGameRankQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest);
        
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("games-rank-bytitle.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;    
    }
    public static function _getGameRankQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "") {
        extract(self::getGameCategoryDetails($gameCat));
        $gameTypeIds = self::mergeGameTypeAndCategories($gameType, $game_category_type);

        $Q ="           
        FROM  report_game_plays E
        LEFT JOIN location L ON L.id = E.location_id
        LEFT JOIN game G ON G.id = E.game_id
        LEFT JOIN game_title GT ON E.game_title_id = GT.id
        LEFT JOIN game_type GTY ON E.game_type_id = GTY.id
        LEFT JOIN debit_type D ON L.debit_type_id = D.id
        WHERE 

        L.reporting = 1 AND
        E.game_id <> 0 AND 
        E.record_status = 1 AND
        E.date_played >= '$dateStart' and E.date_played <= '$dateEnd'";

        if (!empty($location)) {
            $Q .= " AND E.location_id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit)";
        }
        if (empty($onTest) || $onTest == "notest") {
            $Q .= " AND E.game_on_test IN (0)";
        }
        else if($onTest == "all")
        {

        }
        else {
            $Q .= " AND E.game_on_test IN (1)";
        }
        if (!empty($gameTypeIds)) {
            $Q .= " AND GTY.id IN ($gameTypeIds)";
        }

        // GROUP BY
        $Q .= " GROUP BY E.game_title_id ";  
        
        return $Q;

    }
    
    
    public static function getGamePlayQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= "", $sortby = "date_start", $order = ""){
        extract(self::getGameCategoryDetails($gameCat));  
        $dateEnd_ymd = self::dateify($dateEnd);
        $Q = "SELECT 
                E.id,
                E.game_id,
                L.location_name_short as location_name,
                E.debit_type_id,
                D.company as debit_system,
                E.game_title_id,
                T.game_title as game_name,
                if(G.test_piece = 1, 'Yes', 'No') as game_on_test,
                G.not_debit as game_not_debit,
                Y.id as game_type_id,
                Y.game_type,
                '$gameCat' AS game_cat_id,
                '$game_category' AS game_category,                

                '$dateStart' as date_start,
                '$dateEnd_ymd' as date_end,

                E.location_id,
                SUM(E.total_plays) as total_plays, 
                SUM(E.actual_cash) as actual_cash, 
                SUM(E.card_cash) as card_cash,
                SUM(E.card_bonus) as card_bonus,
                SUM(E.actual_cash)+SUM(E.card_cash)+SUM(E.card_bonus) as card_total,
                SUM(E.time_plays) as time_plays,
                SUM(E.product_plays) as product_plays,
                SUM(E.product_notional_value) as product_notional_value,
                SUM(E.courtesy_plays) as courtesy_plays,
                SUM(E.product_and_courtesy_plays) as product_and_courtesy_plays,
                SUM(E.grand_total) as grand_total
                
                ";
        
        $Q .= self::_getGamePlayQuery($dateStart, $dateEnd, $location, $debit, 
                $gameType, $gameCat, $onTest, $gameId, $gameTitleId);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("game-play-detailed.log", "uireports", "UIReports");
        $L->log("Query", $Q);
       return $Q;          
    }
    public static function _getGamePlayQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= ""){
        extract(self::getGameCategoryDetails($gameCat));
        $gameTypeIds = self::mergeGameTypeAndCategories($gameType, $game_category_type);        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        } 
        
        $Q = "
            FROM report_game_plays E
            LEFT JOIN game G ON G.id = E.game_id
            LEFT JOIN game_title T ON T.id = E.game_title_id
            LEFT JOIN game_type Y ON Y.id = T.game_type_id
            LEFT JOIN location L ON L.id = E.location_id
            LEFT JOIN debit_type D ON D.id = E.debit_type_id   
                WHERE E.game_id <> 0 AND G.not_debit = 0 

                AND E.record_status = 1
                ";
                     
        if (!empty($gameTitleId)) {
            $Q .= " AND G.game_title_id IN ($gameTitleId) ";
        }
        if (!empty($gameId)) {
            $Q .= " AND G.id IN ($gameId) ";
        }

        if (!empty($dateStart)) {
            $Q .= " AND E.date_played >= '$dateStart' ";
        }        
        if (!empty($dateEnd)) {
            $Q .= " AND E.date_played <= '$dateEnd 23:59:59' ";
        }        
        if (!empty($location)) {
            $Q .= " AND L.id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit) ";
        }
        if (!empty($onTest)) {
            if ($onTest == "notest") {
                $Q .= " AND G.test_piece IN (0)";
            }
            else if($onTest == "all")
            {

            }
            else {
                $Q .= " AND G.test_piece IN (1)";
            }            
        }
        if (!empty($gameTypeIds)) {
            $Q .= " AND Y.id IN ($gameTypeIds)";
        }
        
        // GROUP BY
        $Q .= " GROUP BY E.game_id, E.location_id ";
        
        return $Q;
    }
    public static function getGamePlayCount($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= ""){
        $Q = "SELECT count(*) as `count` FROM (SELECT E.id ";
        $Q .= self::_getGamePlayQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest, $gameId, $gameTitleId); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;          
    }
           
    public static function getGamesNotPlayedQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= "", $sortby = "date_start", $order = ""){
        extract(self::getGameCategoryDetails($gameCat));        
        $Q = "SELECT E.id,
                E.location_id,
                L.location_name_short as location_name,
                L.debit_type_id,
                D.company as debit_system,
                E.game_id,
                E.game_title_id,
                T.game_title as game_name,
                if(E.game_on_test = 1, 'Yes', 'No') as game_on_test,
                E.game_not_debit,
                Y.id as game_type_id,
                Y.game_type,
                '$gameCat' AS game_cat_id,
                '$game_category' AS game_category,                
                E.game_revenue AS game_total,
                E.date_played as date_start,
                E.date_played as date_end,
                E.date_last_played,
                DATEDIFF(E.date_played, E.date_last_played) as days_not_played
                ";
        
        $Q .= self::_getGamesNotPlayedQuery($dateStart, $dateEnd, $location, $debit, 
                $gameType, $gameCat, $onTest, $gameId, $gameTitleId);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("games-not-played.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;          
        
    }
    public static function _getGamesNotPlayedQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= ""){
        extract(self::getGameCategoryDetails($gameCat));
        $gameTypeIds = self::mergeGameTypeAndCategories($gameType, $game_category_type);        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        }        
        $Q = "
            FROM report_game_plays E
            LEFT JOIN game G ON G.id = E.game_id
            LEFT JOIN location L ON L.id = E.location_id
            LEFT JOIN debit_type D ON D.id = L.debit_type_id   
            LEFT JOIN game_title T ON T.id = E.game_title_id
            LEFT JOIN game_type Y ON Y.id = E.game_type_id
                WHERE E.game_id <> 0  AND E.game_not_debit = 0 
                AND G.sold != 1
                AND E.report_status = 0 AND E.record_status = 1 ";
                     
        if (!empty($gameTitleId)) {
            $Q .= " AND E.game_title_id IN ($gameTitleId) ";
        }
        if (!empty($gameId)) {
            $Q .= " AND E.game_id IN ($gameId) ";
        }

        if (!empty($dateStart)) {
            $Q .= " AND E.date_played >= '$dateStart' ";
        }        
        if (!empty($dateEnd)) {
            $Q .= " AND E.date_played <= '$dateEnd 23:59:59' ";
        }        
        if (!empty($location)) {
            $Q .= " AND E.location_id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit) ";
        }
        if (!empty($onTest)) {
            if ($onTest == "notest") {
                $Q .= " AND E.game_on_test IN (0)";
            }
            else if($onTest == "all")
            {

            }
            else {
                $Q .= " AND E.game_on_test IN (1)";
            }            
        }
        if (!empty($gameTypeIds)) {
            $Q .= " AND Y.id IN ($gameTypeIds)";
        }
        
        // GROUP BY
        $Q .= " ";
        
        return $Q;
    }
    public static function getGamesNotPlayedCount($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= ""){
        $Q = "SELECT count(*) as `count` ";
        $Q .= self::_getGamesNotPlayedQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest, $gameId, $gameTitleId);        
        $count = self::getCountFromQuery($Q);
        return $count;           
    }
        
    
    public static function getMerchandizeExpensesQuery($dateStart, $dateEnd, $location = "", 
            $debit = "", $sortby = "location_id", $order = ""){
        
        $dateEnd = self::dateify($dateEnd);
        $dateStart = date('Y-m-d', strtotime($dateStart. '  first day of this month'));
        $dateEnd = date('Y-m-d', strtotime($dateEnd. ' 23:59:59  last day of this month'));
        
        $Q = "SELECT L.id, 
            L.id as location_id,
            L.location_name_short as location_name,
            L.debit_type_id,
            D.company as debit_system,
            '$dateStart' as date_start,
            '$dateEnd' as date_end,
            IFNULL(LB.budget_value, 0) as merch_budget,
            IFNULL(O.order_total, 0) AS merch_expense, 
            IFNULL(LB.budget_value, 0) - IFNULL(O.order_total, 0) AS utilization 
            ";
        
        $Q .= self::_getMerchandizeExpensesQuery($dateStart, $dateEnd, $location, $debit);
        // ORDER BY
        $sortbys = array(
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        
        
        $L = new MyLog("merch-expesnes.log", "uireports", "UIReports");
        $L->log("Query", $Q);
        return $Q;        
    }
    public static function _getMerchandizeExpensesQuery($dateStart, $dateEnd, $location = "", $debit = ""){
        
        $Q = "
                FROM location L
				LEFT JOIN (
                    SELECT sum(order_total) as order_total, location_id 
                        FROM orders 
                        WHERE date_ordered >= '$dateStart' 
                            and date_ordered <= '$dateEnd' 
                            AND order_type_id IN(7,8) 
                        GROUP BY location_id) O 
                    ON L.id = O.location_id
                LEFT JOIN (
                        SELECT sum(budget_value) as budget_value, location_id 
                            FROM location_budget 
                            WHERE budget_date >= '$dateStart' 
                                and budget_date <= '$dateEnd' 
                            GROUP BY location_id) LB 
                    ON LB.location_id = L.id
                LEFT JOIN debit_type D ON D.id = L.debit_type_id

                WHERE L.can_ship = 1 AND L.active = 1";

        if (!empty($location)) {
            $Q .= " AND L.id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND D.id IN ($debit)";
        }
        
        // GROUP BY
        $Q .= " ";     

        return $Q;        
        
    }
    public static function getMerchandizeExpensesCount($dateStart, $dateEnd, $location = "", $debit = ""){
        $dateEnd = self::dateify($dateEnd);
        $dateStart = date('Y-m-d', strtotime($dateStart. '  first day of this month'));
        $dateEnd = date('Y-m-d', strtotime($dateEnd. ' 23:59:59  last day of this month'));
        $Q = "SELECT count(*) as `count` FROM (SELECT L.id ";
        $Q .= self::_getMerchandizeExpensesQuery($dateStart, $dateEnd, $location, $debit); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;         
    }
        

        
    public static function getLocationRanksQueryERPDB($dateStart, $dateEnd, $location, $debit, $sortby = "pgpd_avg", $order = "")
    {
    }
    public static function getLocationRanksQueryTEMPDB($dateStart, $dateEnd, $location, $debit, $sortby = "pgpd_avg", $order = "")
    {
    }    
    public static function getDumpSummaryQuery()
    {
    }    
    public static function getLocationsReportingQuery() {
    }   
    
    public static function getGamesOnTestRankQuery($dateStart, $dateEnd, 
            $location = "", $debit = "", $sortby = "game_total", $order = "desc")
    {
        
        $Q = "SELECT 
            E.id, 
            E.game_id, 
            L.id as location_id, 
            L.location_name_short as location_name,
            L.debit_type_id,
            D.company as debit_system,
            E.game_title_id, 
            T.game_title as game_name,                 
            sum(IFNULL(E.game_revenue,0)) AS game_total ";            
        
        $Q .= "FROM report_game_plays E
                LEFT JOIN location L ON L.id = E.location_id
                LEFT JOIN debit_type D ON D.id = E.debit_type_id   
                LEFT JOIN game_title T ON T.id = E.game_title_id
                
                WHERE E.game_id <> 0 
                AND E.game_on_test = 1
                AND E.report_status = 1 
                AND E.record_status = 1
                AND E.date_played >= '$dateStart' 
                AND E.date_played <= '$dateEnd'";
        
        if (!empty($location)) {
            $Q .= " AND L.id IN ($location)";
        }
        if (!empty($debit)) {
            $Q .= " AND D.id IN ($debit)";
        }
        $Q .= " Group By E.game_id";
        
        // ORDER BY
        $sortbys = array(
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery; 
        
        return $Q;
    }
    
    public static function getGamesNotOnDebitCardQuery($location = "")
    {
        $locationQuery = "";            
        if (!empty($location)) {
            $locationQuery = " AND game.location_id IN ($location) "; 
        }
        
        $sqlFields = "game.id, 
                   game.game_name, 
                   game.game_title_id,
                   game_title.game_title, 
                   game.location_id,
                   location.location_name_short as location_name,
                   location.debit_type_id,
                   debit_type.company as debit_system,
                   game.not_debit_reason,
                   game.not_debit,
                   game.sold";
        
        $sql = "SELECT $sqlFields 
                FROM game 
                LEFT JOIN game_title ON game_title.id = game.game_title_id
                LEFT JOIN location ON location.id = game.location_id
                LEFT JOIN debit_type ON debit_type.id = location.debit_type_id
                WHERE game.not_debit = 1 AND game.sold = 0 
                AND location.reporting = 1 
                $locationQuery";
        
        return $sql;
    }
    public static function getExcludedReadersQuery($location = "")
    {
        $locationQuery = "";            
        if (!empty($location)) {
            $locationQuery = " AND reader_exclude.loc_id IN ($location) "; 
        }
        
        $sqlFields = "reader_exclude.id, 
                    reader_exclude.loc_id AS location_id,
                    location.location_name_short as location_name, 
                    reader_exclude.debit_type_id, 
                    debit_type.company AS debit_type,
                    reader_exclude.reader_id,
                    reader_exclude.reason";
        
        $sql = "SELECT $sqlFields 
                FROM reader_exclude 
                LEFT JOIN debit_type ON debit_type.id = reader_exclude.debit_type_id
                LEFT JOIN location ON location.id = reader_exclude.loc_id
                WHERE reader_exclude.loc_id <> 0 $locationQuery";
        
        return $sql;        
    }
    
    public static function getRetrySyncStatusQuery($adjustment_date = null, $location = "") {
        $q = "SELECT GA.date_start, GA.loc_id, L.debit_type_id, GA.status, GA.notes, GA.adjustment_date,
            GA.is_past_sync_adjustment
            FROM game_earnings_transfer_adjustments GA 
            LEFT JOIN location L ON L.id=GA.loc_id
            WHERE 
            GA.status = 1 ";
        if (!empty($location)) {
            $q .= " AND GA.loc_id IN ($location) ";
        }
        if (!empty($adjustment_date)) {
            $q .= " OR GA.adjustment_date = '$adjustment_date' ";
        }
        $q .= " ORDER BY GA.date_start DESC";
        return $q;
    }
    public static function getRetrySyncSuccessQuery($adjustment_date = null) {
        $q = "SELECT date_start, loc_id, status, notes, adjustment_date,
            is_past_sync_adjustment
            FROM game_earnings_transfer_adjustments 
            WHERE status = 0 AND notes='ADJUSTED' ";
        if (!empty($adjustment_date)) {
            $q .= " AND adjustment_date = '$adjustment_date' ";
        }
        $q .= " ORDER BY date_start ASC";
        return $q;
    }
    
    
    
    public static function getAdjustmentsQuery()
    {
    }    
    public static function getGamesPlayedQuery()
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
    
    
    /**
     * 
     * @param string $gameCat
     * @return type
     */
    public static function getGameCategoryDetails($gameCat = "all") {
        $game_categories = array(
            "all" => array("label" => "All", "type_id" => "1,2,3,4,5,7,8"),
            "not_attractions" => array("label" => "Not Attractions", "type_id" => "1,2,3,4,5,7"),
            "attractions" => array("label" => "Attractions", "type_id" => "8"),
        );        
        if (empty($gameCat) || empty($game_categories[$gameCat])) {
            $gameCat = "all";
        }
        $game_category = $game_categories[$gameCat]['label'];
        $game_category_type = $game_categories[$gameCat]['type_id'];
        
        return array("game_category" => $game_category, "game_category_type" => $game_category_type);
    }
    /**
     * 
     * @param type $gameType
     * @param type $game_category_type
     * @return type
     */
    public static function mergeGameTypeAndCategories($gameType = "", $game_category_type = "") {
        $gameTypeArray = explode(",", $gameType);
        $catTypeArray = explode(",", $game_category_type);
        
        $gameTypeIdsArray = array();
        if (empty($gameType)) {
            $gameTypeIdsArray = $catTypeArray;
        }
        else {
            if (empty($game_category_type)) {
                $gameTypeIdsArray = $gameTypeArray;
            }
            else {
                $gameTypeIdsArray = array_intersect($gameTypeArray, $catTypeArray);
            }            
        }
        $gameTypeIds = implode(",", $gameTypeIdsArray);
        return $gameTypeIds;
    }

    public static function orderify($sortby = "", $order = "") {
        $sortbyQuery = "";
        if (!empty($sortby)) {
            $sortbyQuery = " ORDER BY $sortby $order";   
        }
        return $sortbyQuery;
    }
    
    public static function getCountFromQuery($Q, $fieldName = 'count') {
        $count = 0;
        $rows = \DB::select($Q);
        if (!empty($rows)) {
            $count = $rows[0]->$fieldName;
        }                
        return $count;                
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
    public static function dateRangeFix(&$startDate = "", &$endDate = "", $includeEndInRange = true, $defaultEndDate = null, $defaultDaysToReport = 7) {
        $newStartDate = trim($startDate);
        $newEndDate = trim($endDate);
        $yesterday = self::dateify("", -1);

        if (empty($newStartDate) && empty($newEndDate) && !empty($defaultEndDate)) {
            $newEndDate = trim($defaultEndDate);
            $newStartDate = date('Y-m-d H:i:s', strtotime($newEndDate.' -' .($defaultDaysToReport-1).' days'));
        }

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
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        $daydiff = $interval->days;
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
     * Human readable date range
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
            if ($dateStartHuman == $dateEndHuman) {
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
    public static function getMenuAccessDetails($parent = 0, $position = 'top', $active = '1') {
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
    
    /****
     * 
     *  OLD FUNCTIONS
     * 
     */
    
    /*//////////////////////////////////////////////////////////////////////
    //PARA: Date Should In YYYY-MM-DD Format
    //RESULT FORMAT:
    // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
    // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
    // '%m Month %d Day'                                            =>  3 Month 14 Day
    // '%d Day %h Hours'                                            =>  14 Day 11 Hours
    // '%d Day'                                                        =>  14 Days
    // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
    // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
    // '%h Hours                                                    =>  11 Hours
    // '%a Days                                                        =>  468 Days
    */
    private static function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }        
    private static function getLastSyncDate() {
        $lastDate = "";//date("Y-m-d", strtotime("-1 day"));
        $selectQuery = "SELECT date_start from game_earnings 
            ORDER BY date_start DESC LIMIT 1";
        $result = \DB::select($selectQuery); 
        if (!empty($result)) {
            $lastDate = date("Y-m-d", strtotime($result[0]->date_start));
        }
        return $lastDate;
    }    
   
    /* depriacated functions */
    
        
    
    public static function getGamePlayQuery_v1($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= "", $sortby = "date_start", $order = ""){
        extract(self::getGameCategoryDetails($gameCat));  
        $dateEnd_ymd = self::dateify($dateEnd);
        $Q = "SELECT 
                E.id,
                G.id as game_id,
                G.location_id,
                L.location_name_short as location_name,
                E.debit_type_id,
                D.company as debit_system,
                G.game_title_id,
                T.game_title as game_name,
                if(G.test_piece = 1, 'Yes', 'No') as game_on_test,
                G.not_debit as game_not_debit,
                Y.id as game_type_id,
                Y.game_type,
                '$gameCat' AS game_cat_id,
                '$game_category' AS game_category,                

                '$dateStart' as date_start,
                '$dateEnd_ymd' as date_end,

                IF(E.debit_type_id = 1,(SUM(E.std_card_credit) + SUM(E.std_card_credit_bonus) + SUM(E.courtesy_plays)),SUM(E.std_plays)) as total_plays, 
                SUM(E.std_actual_cash) as actual_cash, 
                IF(E.debit_type_id = 1,SUM(E.std_card_credit * 1),SUM(E.std_card_dollar)) as card_cash,
                IF(E.debit_type_id = 1,SUM(E.std_card_credit_bonus * 1),SUM(E.std_card_dollar_bonus)) as card_bonus,
                IF(E.debit_type_id = 1,(SUM(E.std_actual_cash)+
								SUM(E.std_card_credit * 1)+
								SUM(E.std_card_credit_bonus * 1)
							),
							   (SUM(E.std_actual_cash)+
								SUM(E.std_card_dollar)+
								SUM(E.std_card_dollar_bonus))
							) as card_total,
                SUM(E.time_plays) as time_plays,
                SUM(E.product_plays) as product_plays,
                ROUND(SUM(E.product_plays * (E.std_card_dollar / E.std_plays)),2)  as product_notional_value,
                SUM(E.courtesy_plays) as courtesy_plays,
                SUM(E.product_plays + E.courtesy_plays) as product_and_courtesy_plays,
                SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END
                    ) as grand_total
                
                ";
        
        $Q .= self::_getGamePlayQuery($dateStart, $dateEnd, $location, $debit, 
                $gameType, $gameCat, $onTest, $gameId, $gameTitleId);    
        // ORDER BY
        $sortbys = array(            
        );
        if (!empty($sortbys[$sortby])) {
            $sortby = $sortbys[$sortby];
        }        
        $sortbyQuery = self::orderify($sortby, $order);
        $Q .= $sortbyQuery;        

        $L = new MyLog("game-play-detailed.log", "uireports", "UIReports");
        $L->log("Query", $Q);
       return $Q;          
    }
    public static function _getGamePlayQuery_v1($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= ""){
        extract(self::getGameCategoryDetails($gameCat));
        $gameTypeIds = self::mergeGameTypeAndCategories($gameType, $game_category_type);        
        if (!empty($dateStart)) {
            $dateStart = self::dateify($dateStart);
        }
        if (!empty($dateEnd)) {
            $dateEnd = self::dateify($dateEnd);
        } 
        
        $gameEarningsJOIN = "SELECT * from game_earnings WHERE 
            game_id <>0 AND date_start >= '$dateStart' AND date_start <= '$dateEnd 23:59:59' ";
        if (!empty($gameId)) {
            $gameEarningsJOIN .= " AND game_id IN ($gameId) ";
        }        
        if (!empty($location)) {
            $gameEarningsJOIN .= " AND loc_id IN ($location) ";
        }
        if (!empty($debit)) {
            $gameEarningsJOIN .= " AND debit_type_id IN ($debit) ";
        }        
        
        $Q = "
            FROM game G
            LEFT JOIN ($gameEarningsJOIN) E ON G.id = E.game_id
            LEFT JOIN game_title T ON T.id = G.game_title_id
            LEFT JOIN game_type Y ON Y.id = T.game_type_id
            LEFT JOIN location L ON L.id = G.location_id
            LEFT JOIN debit_type D ON D.id = E.debit_type_id   
                WHERE E.game_id <> 0 AND G.not_debit = 0 ";
                     
        if (!empty($gameTitleId)) {
            $Q .= " AND G.game_title_id IN ($gameTitleId) ";
        }
        if (!empty($gameId)) {
            $Q .= " AND G.id IN ($gameId) ";
        }

//        if (!empty($dateStart)) {
//            $Q .= " AND E.date_start >= '$dateStart' ";
//        }        
//        if (!empty($dateEnd)) {
//            $Q .= " AND E.date_start <= '$dateEnd 23:59:59' ";
//        }        
        if (!empty($location)) {
            $Q .= " AND L.id IN ($location) ";
        }
        if (!empty($debit)) {
            $Q .= " AND L.debit_type_id IN ($debit) ";
        }
        if (!empty($onTest)) {
            if ($onTest == "notest") {
                $Q .= " AND G.test_piece IN (0)";
            }
            else if($onTest == "all")
            {

            }
            else {
                $Q .= " AND G.test_piece IN (1)";
            }            
        }
        if (!empty($gameTypeIds)) {
            $Q .= " AND Y.id IN ($gameTypeIds)";
        }
        
        // GROUP BY
        $Q .= " GROUP BY G.id, L.id ";
        
        return $Q;
    }
    public static function getGamePlayCount_v1($dateStart, $dateEnd, $location = "", 
            $debit = "", $gameType = "", $gameCat = "all", $onTest = "", 
            $gameId = "", $gameTitleId= ""){
        $Q = "SELECT count(*) as `count` FROM (SELECT E.id ";
        $Q .= self::_getGamePlayQuery($dateStart, $dateEnd, $location, $debit, $gameType, $gameCat, $onTest, $gameId, $gameTitleId); 
        $Q .= ") GD";
        $count = self::getCountFromQuery($Q);
        return $count;          
    }    
   
    public static function buildBlankResultDataDueToNoLocation($message = "", $topMessage = "Report not available", $bottomMessage = "") {
        if (empty($message)) {
            $message = "In order to run reports you must be assigned to at least one location. 
                You have not been assigned to any locations. 
                Please contact an administrator if you believe this to be an error.";
        }
        $result = self::buildBlankResultData($message, $topMessage, $bottomMessage);
        return $result; 
    }
    public static function buildBlankResultData($message = "", $topMessage = "", $bottomMessage = "") {
        $result = self::buildResultData(array(), 0, $message, $topMessage, $bottomMessage);
        return $result; 
    }
    public static function buildResultData($rows = array(), $total = 0, $message = "", $topMessage = "", $bottomMessage = "") {
        $result = array(
                'topMessage' => $topMessage,
                'bottomMessage' => $bottomMessage,
                'message' => $message,
                'rows'=> $rows, 
                'total' => $total
            );
        return $result; 
    }
}
