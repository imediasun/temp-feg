<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class trainingmaterial extends Sximo  {
	
	protected $table = 'img_uploads';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT img_uploads.id,img_uploads.users,img_uploads.date,img_uploads.video_path,img_uploads.video_title from img_uploads";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE img_uploads.image_category='video' AND img_uploads.id IS NOT NULL  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
    function get_youtube_id_from_url($url)
    {
        if (stristr($url,'youtu.be/'))
        {preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID); return $final_ID[4]; }
        else
        {@preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD); return $IDD[5]; }
    }

}
