<?php if (!defined('APPLICATION')) exit();

$PluginInfo['KarmaLeaderBoard'] = array(
   'Name' => 'KarmaLeaderBoard',
   'Description' => "Karma Leader Board - sidepanel with user photo(or vanillicon or name) and karma points for top karma point earners",
   'Version' => '1.5',
   'Requires' => FALSE, 
   'Author' => "Peregrine",
   'SettingsUrl' => '/dashboard/settings/karmaleaderboard',
);

class KarmaLeaderBoardPlugin extends Gdn_Plugin {
  
   public function Base_Render_Before($Sender) {
    $Controller = $Sender->ControllerName;
	$ShowOnController = array(
					'discussioncontroller',
					'categoriescontroller',
					'discussionscontroller',
				);
   if (!InArrayI($Controller, $ShowOnController)) return; 

   
     include_once(PATH_PLUGINS.DS.'KarmaLeaderBoard'.DS.'class.karmaleaderboardmodule.php');  
     $Sender->AddCssFile('klb.css', 'plugins/KarmaLeaderBoard');
    
     $KarmaLeaderBoardModule = new KarmaLeaderBoardModule($Sender);
     $Sender->AddModule($KarmaLeaderBoardModule);
   }
 
public function SettingsController_KarmaLeaderBoard_Create($Sender) {
        $Session = Gdn::Session();
        $Sender->Title('Karma Leader Board');
        $Sender->AddSideMenu('plugin/karmaleaderboard');
        $Sender->Permission('Garden.Settings.Manage');
        $Sender->Form = new Gdn_Form();
        $Validation = new Gdn_Validation();
        $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
        $ConfigurationModel->SetField(array(
            'Plugins.KarmaLeaderBoard.Limit'
        ));
        $Sender->Form->SetModel($ConfigurationModel);


        if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
            $Sender->Form->SetData($ConfigurationModel->Data);
        } else {
            $Data = $Sender->Form->FormValues();

            if ($Sender->Form->Save() !== FALSE)
                $Sender->StatusMessage = T("Your settings have been saved.");
        }
        $Sender->Render($this->GetView('klb-settings.php'));
 

  }
 
   
   public function Setup() {

   }
}
