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
            $file = "default";
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
            $this->logger->addInfo($msg, $obj);
        }        
	}        
}


