<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Envconfiguration extends Sximo  {
	
	protected $table = 'env_settings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT env_settings.* FROM env_settings  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE env_settings.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    /**
     * @param $key
     * @return bool
     */
    public static function isExtraVariable($key){

        $extraVariables = [
            "PATH",
            "TZ",
            "SCRIPT_NAME",
            "REQUEST_URI",
            "QUERY_STRING",
            "REQUEST_METHOD",
            "SERVER_PROTOCOL",
            "GATEWAY_INTERFACE",
            "REDIRECT_URL",
            "REMOTE_PORT",
            "SCRIPT_FILENA",
            "SERVER_ADMIN",
            "CONTEXT_DOCUMENT_ROOT",
            "CONTEXT_PREFIX",
            "REQUEST_SCHEME",
            "DOCUMENT_ROOT",
            "REMOTE_ADDR",
            "SERVER_PORT",
            "SERVER_ADDR",
            "SERVER_NAME",
            "SERVER_SOFTWARE",
            "SERVER_SIGNATURE",
            "LD_LIBRARY_PATH",
            "HTTP_X_HTTPS",
            "HTTP_COOKIE",
            "HTTP_ACCEPT_LANGUAGE",
            "HTTP_ACCEPT_ENCODING",
            "HTTP_ACCEPT",
            "HTTP_USER_AGENT",
            "HTTP_UPGRADE_INSECURE_REQUESTS",
            "HTTP_CACHE_CONTROL",
            "HTTP_PRAGMA",
            "HTTP_CONNECTION",
            "HTTP_HOST",
            "SSL_TLS_SNI",
            "HTTPS",
            "SCRIPT_URI",
            "SCRIPT_URL",
            "UNIQUE_ID",
            "REDIRECT_STATUS",
            "REDIRECT_SSL_TLS_SNI",
            "REDIRECT_HTTPS",
            "REDIRECT_SCRIPT_URI",
            "REDIRECT_SCRIPT_URL",
            "REDIRECT_UNIQUE_ID",
            "FCGI_ROLE",
            "PHP_SELF",
            "REQUEST_TIME_FLOAT",
            "REQUEST_TIME",
            "argv",
            "argc",
        ];

        return !in_array($key,$extraVariables);

    }
}
