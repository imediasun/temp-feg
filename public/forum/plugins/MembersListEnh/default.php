<?php if(!defined('APPLICATION')) die();

$PluginInfo['MembersListEnh'] = array(
	'Version' => '6.3',
	'Name' => 'Members List Enhanced',
	'Description' => 'This plugin provides a table of users in the forum.  The table is sortable by column and has pagination.  Links from photo, name, and individual discussion and comment count go to appropriate pages.  Settings for number of users to display per page, and permissions for viewing is in config.php. Option to view columns from KarmaBank Plugin, Thankful Plugin and LikeThis Plugin.',
	'SettingsUrl' => '/dashboard/settings/memberslistenh',
	'RegisterPermissions' => array('Plugins.MembersListEnh.IPEmailView','Plugins.MembersListEnh.GenView'),
	'Author' => 'peregrine'
);


class MembersListEnhPlugin extends Gdn_Plugin {

    public function Base_Render_Before($Sender) {
        $Session = Gdn::Session();

        if (($Sender->Menu) &&
                ((CheckPermission('Plugins.MembersListEnh.GenView')) ||
                (CheckPermission('Plugins.MembersListEnh.IPEmailView')))) {
                 $Sender->Menu->AddLink('Members', T('Members'), 'members');
        }
    }

    public function SettingsController_MembersListEnh_Create($Sender) {
        $Session = Gdn::Session();
        $Sender->Title('Members List Enhanced');
        $Sender->AddSideMenu('plugin/memberslistenh');
        $Sender->Permission('Garden.Settings.Manage');
        $Sender->Form = new Gdn_Form();
        $Validation = new Gdn_Validation();
        $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
        $ConfigurationModel->SetField(array(
            'Plugins.MembersListEnh.DCount',
            'Plugins.MembersListEnh.ShowPhoto', 
            'Plugins.MembersListEnh.ShowSymbol',
            'Plugins.MembersListEnh.ShowPeregrineReactions',
            'Plugins.MembersListEnh.ShowLike', 
            'Plugins.MembersListEnh.ShowThank', 
            'Plugins.MembersListEnh.ShowKarma', 
            'Plugins.MembersListEnh.ShowAnswers', 
            'Plugins.MembersListEnh.ShowID',     
            'Plugins.MembersListEnh.ShowRoles', 
            'Plugins.MembersListEnh.ShowFVisit', 
            'Plugins.MembersListEnh.ShowLVisit',
            'Plugins.MembersListEnh.ShowEmail', 
            'Plugins.MembersListEnh.ShowIP',  
            'Plugins.MembersListEnh.ShowVisits',
            'Plugins.MembersListEnh.ShowDiCount',
            'Plugins.MembersListEnh.ShowCoCount'
      
      
      
        ));
        $Sender->Form->SetModel($ConfigurationModel);


        if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
            $Sender->Form->SetData($ConfigurationModel->Data);
        } else {
            $Data = $Sender->Form->FormValues();

            if ($Sender->Form->Save() !== FALSE)
                $Sender->StatusMessage = T("Your settings have been saved.");
        }
        $Sender->Render($this->GetView('mle-settings.php'));
    }

    public function PluginController_MembersListEnh_Create($Sender) {
        $Session = Gdn::Session();

        if (($Sender->Menu) &&
                ((CheckPermission('Plugins.MembersListEnh.GenView')) ||
                (CheckPermission('Plugins.MembersListEnh.IPEmailView')))) {
            $Sender->ClearCssFiles();
            $Sender->AddCssFile('style.css');
            $Sender->MasterView = 'default';

            $Sender->Render('memtable', '', 'plugins/MembersListEnh');
        }
    }
    public function OnDisable() {
	          $matchroute = '^members(/.*)?$';
             
	           Gdn::Router()-> DeleteRoute($matchroute); 
	
	}
    public function Setup() {
  
             $matchroute = '^members(/.*)?$';
             $target = 'plugin/MembersListEnh$1';
        
             if(!Gdn::Router()->MatchRoute($matchroute))
                  Gdn::Router()->SetRoute($matchroute,$target,'Internal'); 
          
    }
}

