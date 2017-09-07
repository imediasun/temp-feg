<?php
if(!defined('APPLICATION')) die();

$PluginInfo['Browsi'] = array(
    'Name' => 'Brow.si',
    'Description' => 'Adding Brow.si to Vanilla makes search, sharing, notifications and other engagement utilities consistently available to mobile users from anywhere on your forum.',
    'Version' => '1.0.2',
    'Author' => "MySiteApp Ltd",
    'AuthorEmail' => 'vanillaforums@brow.si',
    'AuthorUrl' => 'https://brow.si',
    'MobileFriendly' => TRUE,
    'SettingsUrl' => '/dashboard/settings/browsi',
    'SettingsPermission' => 'Garden.Settings.Manage'
);

class BrowsiPlugin extends Gdn_Plugin {
    /**
     * Appends Brow.si's JavaScript code to the page footer.
     * @param $Sender
     * @param $Args
     */
    public function Base_AfterRenderAsset_Handler($Sender, $Args) {
        if ($Args['AssetName'] != "Foot") {
            return;
        }
        $siteId = C('Plugins.Browsi.SiteID', '');
?>
<script type="text/javascript">
    (function(w, d){
<?php if (!empty($siteId)): ?>
        w['_brSiteId'] = '<?php echo $siteId ?>';
<?php endif; ?>
        w['_brPlatform'] = ['vanillaforums', '<?php echo APPLICATION_VERSION ?>'];
        function br() {
            var i='browsi-js'; if (d.getElementById(i)) {return;}
            var siteId = /^[a-zA-Z0-9]{1,7}$/.test(w['_brSiteId']) ? w['_brSiteId'] : null;
            var js=d.createElement('script'); js.id=i; js.async=true;
            js.src='//js.brow.si/' + ( siteId != null ? siteId + '/' : '' ) + 'br.js';
            (d.head || d.getElementsByTagName('head')[0]).appendChild(js);
        }
        d.readyState == 'complete' ? br() :
            ( w.addEventListener ? w.addEventListener('load', br, false) : w.attachEvent('onload', br) );
    })(window, document);
</script>
<?php
    }

    public function AuthenticationController_Render_Before($Sender, $Args) {
        if (isset($Sender->ChooserList)) {
            $Sender->ChooserList['browsi'] = 'Brow.si';
        }
        if (is_array($Sender->Data('AuthenticationConfigureList'))) {
            $List = $Sender->Data('AuthenticationConfigureList');
            $List['browsi'] = '/dashboard/settings/browsi';
            $Sender->SetData('AuthenticationConfigureList', $List);
        }
    }

    /**
     * Change Brow.si Site ID settings
     * @param $Sender
     * @param $Args
     */
    public function SettingsController_Browsi_Create($Sender, $Args) {
        $Sender->Permission('Garden.Settings.Manage');
        if ($Sender->Form->IsPostBack()) {
            $Settings = array(
                'Plugins.Browsi.SiteID' => $Sender->Form->GetFormValue('SiteID'));

            SaveToConfig($Settings);
            $Sender->InformMessage(T("Your settings have been saved."));

        } else {
            $Sender->Form->SetFormValue('SiteID', C('Plugins.Browsi.SiteID', ''));
        }

        $Sender->AddSideMenu();
        $Sender->SetData('Title', T('Brow.si Settings'));
        $Sender->Render('Settings', '', 'plugins/Browsi');
    }
}