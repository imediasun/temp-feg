<?php
/**
 * Created by PhpStorm.
 * User: NSC
 * Date: 6/16/2016
 * Time: 4:10 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Input;
use DB;

class DemoController extends Controller
{


    public function getIndex()

    {

        //$company = DB::table('company')->get();
        //return view('demo.index', ['company' => $company]);


        //Game with not matched location with last history log
        $csv = "ASSET ID, GAME NAME, HISTORY LOG ID \n";
        $games = \DB::select("
            SELECT game.id AS id, game.game_name AS name
            FROM game
            LEFT JOIN game_move_history ON game.id = game_move_history.game_id
            WHERE game.location_id != (SELECT to_loc FROM game_move_history WHERE game_id = game.id ORDER BY id DESC LIMIT 0,1) GROUP BY game.id");

        foreach ($games as $index => $game){
            $csv .= "$game->id, $game->name, Last movement log not exist \n";
        }

        //Game with history conflicts
        $games = \DB::select("
            SELECT game.id AS id, game.game_name AS name
            FROM game
            LEFT JOIN game_move_history ON game.id = game_move_history.game_id
            WHERE game.location_id = (SELECT to_loc FROM game_move_history WHERE game_id = game.id ORDER BY id DESC LIMIT 0,1) GROUP BY game.id");

        foreach ($games as $game){
            $history = \DB::select("SELECT * from game_move_history WHERE game_id=$game->id");

            foreach ($history as $key => $row){
                if($key+1>=count($history)){break;}
                if($row->to_loc != $history[$key+1]->from_loc){
                    $csv .= "$row->game_id, $game->name, $row->id \n";
                    break;
                }
            }
        }


        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="Game-History-Conflict-Report.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $csv;

    }

}