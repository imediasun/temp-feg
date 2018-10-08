<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reader extends Sximo
{
    protected $table = 'readers';
    protected $primaryKey = 'id';
  //  protected $fillable = ['reader_id', 'game_id', 'location_id', 'reporting_reader_log','date_added'];
    public $timestamps = false;

    /**
     * @return mixed
     */
    public static function getAllReaders()
    {
        $readers = self::select('reader_id', 'game_id', 'location_id','first_report_date','last_report_date', 'reporting_reader_log', 'date_added')
            ->where("reporting_reader_log", '=', '1')
            ->groupby('reader_id')
            ->groupby('game_id')
            ->groupby('location_id')->get();
        return $readers;
    }

    /**
     * @param $gameReaders
     */
    public static function updateReaders($gameReaders){

        $reader = self::where("location_id",$gameReaders->location_id);
        $readers = $reader->where('game_id',$gameReaders->game_id)->where("reader_id",$gameReaders->reader_id)->first();
        if($readers){
            $readers->last_report_date=date('Y-m-d',strtotime($gameReaders->date_start));
            $readers->save();
        }else{
            $readerData= [
                'game_id'=>$gameReaders->game_id,
                'location_id'=>$gameReaders->location_id,
                'reader_id'=>$gameReaders->reader_id,
                'first_report_date'=>date('Y-m-d'),
                'last_report_date'=> date('Y-m-d',strtotime($gameReaders->date_start)),
                'reporting_reader_log'=>1
            ];
            $reader = new self();
            $reader->insertRow($readerData,0);
            $game = game::where('id',$gameReaders->game_id);
            $gameRecord = $game->where('location_id',$gameReaders->location_id)->first();

            if($gameRecord) {
                $gameRecord->total_readers = $gameRecord->total_readers+1;
                $gameRecord->save();
            }
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public static function getReaderNotPlayed($params = [])
    {
        $location = null;
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location'=> location::reportingLocations(),
        ), $params));
        $sqlExtendedColumn1 = "(SELECT
     COUNT(tr.reader_id)
   FROM readers tr
   WHERE tr.game_id = readers.game_id
       AND tr.location_id = readers.location_id
       AND tr.last_report_date = '$date') AS total_reader_reported ";
        $sqlExtendedColumn2 = "(game.total_readers - (SELECT
     COUNT(tr.reader_id)
   FROM readers tr
   WHERE tr.game_id = readers.game_id
       AND tr.location_id = readers.location_id
       AND tr.last_report_date = '$date')) AS total_reader_not_reporing ";

        $selectColumns = ' group_concat(readers.reader_id) as reader_id,'.$sqlExtendedColumn1.',game.total_readers,
         '.$sqlExtendedColumn2.',readers.game_id,readers.location_id,location.location_name,
         game.game_name,game_title.game_title, if( game.game_title_id > 0,game_title.game_title,game.game_name) as gameTitle';
        $readers = self::select(\DB::raw($selectColumns))->where('last_report_date', '<', $date);

        $readers->leftJoin('game',function ($join){
            // 'game.id', '=', 'readers.game_id'
            $join->on('game.id', '=', 'readers.game_id');
            $join->on('game.location_id', '=', 'readers.location_id');
        });
        $readers->leftJoin('game_title', 'game_title.id', '=', 'game.game_title_id');
        $readers->leftJoin('location', 'location.id', '=', 'readers.location_id');
       /* if(!is_null($location)) {
            $readers->whereIn('readers.location_id', explode(",",$location));
        }*/
        $readerData = $readers->where(\DB::raw('Year(readers.date_added)'), ">=", '2018')

            ->whereNotNull('game.total_readers')
            ->where('game.total_readers','>',1)
            ->having('total_reader_reported','>',0)
            ->having('game.total_readers','>','total_reader_reported')
            ->groupby('readers.game_id')->groupby('readers.location_id')->whereNotNull('game_title.game_title')->get();

        return $readerData;
    }

}
