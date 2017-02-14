<?php

namespace App\Library\FEG\System\Email;

use PDO;
use DB;
use App\Library\MyLog;
use App\Library\FEG\System\FEGSystemHelper;

class GenericReports
{  
    private static $L;
    
    public static function newGameReceived($params = array()) {        
        global $__logger;
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('receive.log', 'FEGCronTasks/new-game', 'GAME');
        $params['_logger'] = self::$L = $__logger = $L;
        $game = $params['game'];
        
        $gameId = $game->id;
        $gameTitle = $game->game_title;
        $locationId = $game->location_id;
        $locationName = $game->location_name;
        $assetTagPath = $game->assetTag;
        
        $message = "Click link for detail: <a href='".url("/mylocationgame/?gamedetails=" . $gameId)."' target='_blank' >$gameId - $gameTitle at $locationId | $locationName</a>";
        
        $configName = 'New Game Received';
        $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($configName, $locationId);
        FEGSystemHelper::sendSystemEmail(array_merge($emailRecipients, array(
            'subject' => "NEW Game $gameTitle has been received at $locationId", 
            'message' => $message, 
            'isTest' => true,
            'configName' => $configName,
            'configNamePrefix' => '',
            'configNameSuffix' => ''.$gameId. '-'. $locationId,
            'attach' => [$assetTagPath],
        )));        
        
    }
       
    
}
