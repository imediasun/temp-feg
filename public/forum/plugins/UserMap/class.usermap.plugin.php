<?php if (!defined('APPLICATION')) exit();

$PluginInfo['UserMap'] = array(
    'Name' => 'User Map',
    'Description' => "Shows a map with a marker for geolocation of all active users",
    'Version' => '0.01',
    'Author' => 'R_J',
    'RequiredApplications' => array('Vanilla' => '>=2.0.18'),
    'RequiredTheme' => FALSE, 
    'RequiredPlugins' => 'GeoIPData',
    'RegisterPermissions' => FALSE,
    'SettingsPermission' => FALSE,
    'HasLocale' => TRUE,
    'License' => 'GPLv2'
);

class UserMapPlugin extends Gdn_Plugin {
   public function Setup() {
      // change the update interval to decide how often the query should be generated
      if (!C('Plugins.UserMap.UpdateInterval')) {
         SaveToConfig('Plugins.UserMap.UpdateInterval', 2 * 60);
      }

      // change to decide which users should be shown in usermap
      if (!C('Plugins.UserMap.ActiveDays')) {
         SaveToConfig('Plugins.UserMap.ActiveDays', 7);
      }
   }

    public function Base_Render_Before($Sender) {
        // Add Module only if we are at one of these controllers
        if (!InArrayI($Sender->ControllerName, array('discussioncontroller', 'discussionscontroller', 'categoriescontroller', 'categorycontroller', 'profilecontroller', 'activitycontroller', 'draftscontroller'))) {
            return;
        }
        // we need some css
        $Sender->Head->AddCss(DS.'plugins'.DS.'UserMap'.DS.'design'.DS.'custom.css');
        
        include_once(PATH_PLUGINS.DS.'UserMap'.DS.'class.usermapmodule.php');
        $UserMapModule = new UserMapModule($Sender);
        $Sender->AddModule($UserMapModule);
    } // End of Base_Render_Before
} // End of UserMapPlugin