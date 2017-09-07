<?php if(!defined('APPLICATION')) exit();

class KarmaLeaderBoardModel extends VanillaModel {

    public function GetKarmaBal ($limit) {
      
      
        $KarmaLeaderBoardModel = new Gdn_Model('User');
        $KLBData = $KarmaLeaderBoardModel->SQL
        ->Select('u.UserID, u.Name,u.Email, u.Photo, kb.Balance')
        ->From('KarmaBankBalance kb')
        ->Join('User u', 'kb.UserID = u.UserID')
        ->OrderBy("kb.Balance", "Desc")    
        ->Limit("$limit")
        ->Get()
        ->ResultArray();
     
        return $KLBData;
    }  
       
  
}

