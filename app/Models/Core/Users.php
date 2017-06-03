<?php namespace App\Models\Core;

use App\Models\Sximo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Users extends Sximo  {
	
	protected $table = 'users';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return " SELECT  users.*,  tb_groups.name, users.group_id, users.username,
                IF(has_all_locations = 0,(SELECT GROUP_CONCAT(DISTINCT location_name SEPARATOR ', ') FROM user_locations JOIN location ON location.id = user_locations.location_id WHERE user_id = users.id GROUP BY user_id) ,\"All Locations\") AS has_all_locations
                FROM users LEFT JOIN tb_groups ON tb_groups.group_id = users.group_id ";
	}	

	public static function queryWhere( $id = null ){
        $return ="Where users.id is not null ";
        if($id != null)
        {
            $return .= " AND users.id = $id";
        }
        return $return;
	}
	
	public static function queryGroup(){
		return "      ";
	}

	/**
	 * override location drop down menu
	 * @param $params
	 * @param null $limit
	 * @param null $parent
	 * @return mixed
	 */
	public static function getComboselect($params, $limit = null, $parent = null)
	{

		$tableName = $params[0];
		if ($tableName == 'location'){
			$locations = \DB::table('location')
				->select('location.*')
				->where('location.active', 1)->orderBy('location.location_name')
				->get();
			return $locations;
		} else {
			return parent::getComboselect($params, $limit, $parent);
		}
	}

	public static function verifyOAuthTokenIsValid($oauthToken){
	    if(empty($oauthToken)){
	        return false;
        }
        $client = new Client();
        $res = $client->request('GET', "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=$oauthToken");
        $result = $res->getBody();
        $result = json_decode($result, true);
        if(isset($result['error'])){
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function refreshOAuthToken($refreshToken){

        $client = new Client();
        $res = $client->request('POST', 'https://www.googleapis.com/oauth2/v4/token',array('headers'=>array('Content-Type'=>'application/x-www-form-urlencoded'),'form_params'=>array(
            'grant_type'=>'refresh_token',
            'approval_prompt'=>'force',
            'access_type'=>'offline',
            'client_id'=>env('G_ID'),
            'refresh_token'=>$refreshToken,
            'client_secret'=>env('G_SECRET'))));
        $result = $res->getBody();
        $array = json_decode($result, true);
        if(!empty($array)){
            return $array;
        }
        else
        {
            return false;
        }
    }

    public function isOAuthRefreshedRecently(){
        if(empty($this->oauth_refreshed_at)){
            return false;
        }
        $oAuthRefreshed = \DateTime::createFromFormat('Y-m-d H:i:s',$this->oauth_refreshed_at)->getTimestamp();
        $oAuthRefreshed += (55*60.00);
        $now = new \DateTime();
        $now = $now->getTimestamp();

        if($oAuthRefreshed >= $now){
            return true;
        }
        else
        {
            return false;
        }
    }

    public function updateRefreshToken($data){

        $userWithRelatedOAuthEmails = Users::where('oauth_email',$this->oauth_email)->where('id','!=',$this->id)->get();
        //$L->log(count($users_with_related_oauthemail).' Users with same oauth email Found');
        foreach ($userWithRelatedOAuthEmails as $related)
        {
            $related->oauth_token = $data['access_token'];
            if(isset($data['refresh_token'])){
                $related->refresh_token = $data['refresh_token'];
            }
            $related->save();
            //$L->log('ID '.$related->id.' User (with same oauth email) oauth token updated');
        }
        $this->oauth_token = $data['access_token'];
        if(isset($data['refresh_token'])){
            $this->refresh_token = $data['refresh_token'];
        }
        $this->oauth_refreshed_at = date('Y-m-d H:i:s');
        $this->save();
    }

}
