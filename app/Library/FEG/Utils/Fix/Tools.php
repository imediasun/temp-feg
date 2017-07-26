<?php

namespace App\Library\FEG\Utils\Fix;

use PDO;
use DB;
use File;
use Mail;
use FEGHelp;
use FEGFormat;
use Carbon\Carbon;
use App\Models\Sximo;
use App\Models\Core\Users;
use App\Models\Feg\System\Options;


class Tools
{
    private static $L;

    //FEGHelp::setLogger($_logger, $name = "elm5-utils-fix-tools.log", $path = "Utils/Fix/Tools", $id = "FIX")
    //FEGHelp::logit($obj = "", $file = "elm5-utils-fix-tools.log", $pathsuffix ="Utils/Fix/Tools/Data", $skipDate = false)
    //FEGHelp::tableFromArray($array = array(), $options = array())
    //FEGHelp::sanitizeTitleToId($title)
    //FEGHelp::getHumanDate($date)
    //FEGHelp::split_trim($txt, $delim = ',', $trimChar = null)
    //FEGHelp::joinArray($array, $groupOn = '', $concatOn = array(), $sumOn = array(), $ignore = array(), $options = array())
    //FEGHelp::split_trim_join($txt, $delim = ',', $trimChar = null)

    public static function gameIdAnalyzer($gameId = '') {


        // show history
        // show current location, prev_location, from game table
        // show from game_earnings last data received, generate move history
        $tableOptions = ["tableClass" => "table table-striped datagrid", "humanifyTitle" => true, "tableStyles" => "font-size: 14px;"];

        $ret = [];
        if (empty($gameId)) {
            $ret = ["No game Id provided"];
            return $ret;
        }
        $model = new Sximo();
        $moveHistory = $model->getMoveHistory($gameId);
        $ret[] = "<h3>Move History</h3>";
        if (!empty($moveHistory)) {
            $move = json_decode(json_encode($moveHistory), true);
            //'to_first_name','to_last_name',
            $skip = ['skip'=> ['from_first_name','from_last_name','from_by','to_by','from_loc','to_loc']];
            $ret[] = FEGHelp::tableFromArray($move, array_merge($skip, $tableOptions));
        }
        else {
            $ret[] = "<font color='orange'>None</font>";
        }

        $ret[] = "<hr/>";
        $ret[] = "<h3>Game Table's Data</h3>";
        $gameData = DB::table('game')->select(
                'location_id',
                'prev_location_id',
                'date_in_service', 
                'game_move_id',
                'date_last_move',
                'status_id', 
                'sold', 
                'not_debit',
                'test_piece'
                
                )->where('id', $gameId)->first();
        if (empty($gameData)) {
            $ret = ["<font color='red'>No data in game table!</font>"];
        }
        else {
            $gameDetails = [
                ["Field" => "Status", "Value" => $gameData->status_id == "3" ? "Transit" : ($gameData->status_id == "2" ? "Repair" : "Up")],
                ["Field" => "Location", "Value" => $gameData->location_id],
                ["Field" => "Prev Location", "Value" => $gameData->prev_location_id],
                ["Field" => "Inception Date", "Value" => \DateHelpers::formatDate($gameData->date_in_service)],
                ["Field" => "Not Debit?", "Value" => $gameData->not_debit == 1 ? "Yes" : "No"],
                ["Field" => "Is Test?", "Value" => $gameData->test_piece == 1 ? "Yes" : "No"],
                ["Field" => "Last Moved on", "Value" => \DateHelpers::formatDate($gameData->date_last_move)],
                ["Field" => "Last Move ID", "Value" => $gameData->game_move_id],
                ["Field" => "Sold?", "Value" => $gameData->sold == 1 ? "Yes" : "No"],
            ];
            $tConfig = ["cellWidths" => ["Field" => "150"]];
            $ret[] = FEGHelp::tableFromArray($gameDetails, array_merge($tConfig, $tableOptions));
        }


        $ret[] = "<hr/>";
        $ret[] = "<h3>Game Earnings Data</h3>";
        $earningsData = DB::table('game_earnings')
                ->select('loc_id', 'date_start')
                ->where('game_id', $gameId)
                ->groupBy('loc_id')
                ->orderBy('date_start')
                ->get();


        if (empty($earningsData)) {
            $ret[] = "<font color='red'>No Earnings data found!</font>";
        }
        else {
            $cHistory = [];
            foreach($earningsData as $earnings) {
                $loc = $earnings->loc_id;
                $maxDate = DB::table('game_earnings')->where('loc_id', $loc)
                            ->where('game_id', $gameId)
                            ->max('date_start');
                $minDate = DB::table('game_earnings')->where('loc_id', $loc)
                            ->where('game_id', $gameId)
                            ->min('date_start');

                $cHistory[] = ['Location' => $loc, 'From' => $minDate, 'To' => $maxDate];
            }
            $ret[] = FEGHelp::tableFromArray($cHistory, $tableOptions);
        }


        $ret[] = "<br/><br/><hr/><h3>Helpers</h3>";
        $ret[] = "<strong>SQL TO INSERT GAME HISTORY</strong>";
        $ret[] = "<br/><pre>INSERT INTO `game_move_history` SET
    game_id=$gameId,
    from_loc=?,
    from_by=238,
    from_date='? 23:59:59',
    to_loc=?,
    to_by=238,
    to_date='? 00:00:00'</pre>";

    $ret[] = "<br/><strong>SQL TO UPDATE GAME</strong>";
    $ret[] = "<br/><pre>UPDATE game SET
        location_id=?,
        prev_location_id=?,
        date_last_move='?',
        game_move_id=?

    WHERE id=$gameId</pre>";

    $ret[] = "<br/><strong>SQL TO UPDATE GAME PLAY REPORT SUMMARY</strong>";
    $ret[] = "<br/><pre>UPDATE report_game_plays SET
        location_id=?
    WHERE
            game_id=$gameId
        AND date_played >='?'
        AND date_played <='?'
        AND location_id != ?</pre>";

        return $ret;

    }

}
