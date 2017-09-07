<?php if (!defined('APPLICATION')) exit();

class KarmaLeaderBoardModule extends Gdn_Module {
   

 public function AssetTarget() {
      return 'Panel';
   }

 public function ToString() {
  

    echo "<div id=\"KarmaLeaderBoard\" class=\"Box KarmaLeaderBoardBox\">";
 
    echo "<h4>";
    echo T('Karma Leaders');
    echo "</h4>";
 
    
       echo '<ul class="PanelInfo PanelKarmaBankBalance">';
             $limit =   C('Plugins.KarmaLeaderBoard.Limit');
             if ($limit < 1)   $limit = 5;
             $KLBArray = KarmaLeaderBoardModel::GetKarmaBal($limit);
     
             
             for($x=0;$x < $limit; $x++) {
             
             $UID =    $KLBArray[$x]['UserID'];  
             $Name =  $KLBArray[$x]['Name'];  
             $Photo =  $KLBArray[$x]['Photo']; 
             $Balance =  $KLBArray[$x]['Balance']; 
             $Email = $KLBArray[$x]['Email'];
          
              $Object->UserID = $UID;
              $Object->Name = $Name;
              $Object->Photo = $Photo; 
              $Object->Email = $Email; 
              $User = UserBuilder($Object);
              $photo =   UserPhoto($User,array('LinkClass'=>'ProfilePhotoCategory','ImageClass'=>'ProfilePhotoKarma'));
           echo '<p class="klbspacer" ></p>';
           echo '<li class="klbanchor">';
        
            $Href =  "/profile/$Name/$UID";
            if ($photo) {
            echo UserPhoto($User,array('LinkClass'=>'ProfilePhotoCategory','ImageClass'=>'ProfilePhotoKarma'));
             echo UserAnchor($User);
            } else  {
            echo UserAnchor($User);
            }
             echo "<span class=\"Aside\"><span class=\"Count\">" . T('Points: ') . $Balance . "</span></span>";
            echo '</li>';
         }
     echo "</ul>";
      echo "</div>";


   }
}
