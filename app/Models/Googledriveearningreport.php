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
		
		return "  SELECT google_drive_earning_reports.* FROM google_drive_earning_reports  ";
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

}
