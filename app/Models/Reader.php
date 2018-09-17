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
        $readers = self::select('reader_id', 'game_id', 'location_id', 'reporting_reader_log', 'date_added')
            ->where("reporting_reader_log", '=', '1')
            ->groupby('reader_id')
            ->groupby('game_id')
            ->groupby('location_id')->get();
        return $readers;
    }

}
