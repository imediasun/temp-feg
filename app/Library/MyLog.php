<?php
namespace App\Library;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MyLog
{
	private $logger;
    /**
     * 
     * @param string $file
     * @param type $depth
     * @param string $type
     */
	public function __construct($file = "", $depth = "", $type = "")
	{
        if (empty($type)) {
            $type = "Default";
        }
        $this->logger = new Logger($type);
        if (empty($file)) {
            $file = "default.log";
        }
        $fileprefix = "log-" . date("Ymd") . (empty($file) ? "": ("-".$file));
        $path = storage_path() . '/logs' . (empty($depth) ? "": ("/".$depth));        
        $filepath = $path . '/'. $fileprefix;        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $this->logger->pushHandler(new StreamHandler($filepath, Logger::INFO));
            
	}    
    
	public function log($msg = "", $obj = '') {
        if (empty($obj)) {
            $this->logger->addInfo($msg);
        }
        else {
            if (!is_array($obj)) {
                $obj = array($obj);
            }
            $this->logger->addInfo($msg, $obj);
        }        
	}
	public function error($msg = "", $obj = '') {
        if (empty($obj)) {
            $this->logger->addError($msg);
        }
        else {
            if (!is_array($obj)) {
                $obj = array($obj);
            }
            $this->logger->addError($msg, $obj);
        }        
	}      
}


