<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '2.0.18.8';

// Database
$Configuration['Database']['Name'] = 'fegllc_forum_new';
$Configuration['Database']['Host'] = 'localhost';
$Configuration['Database']['User'] = 'fegllc_nsmith';
$Configuration['Database']['Password'] = 'Pa55word';

// EnabledApplications
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';

// EnabledPlugins
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';
$Configuration['EnabledPlugins']['jsconnect'] = TRUE;
$Configuration['EnabledPlugins']['jsconnectAutoSignIn'] = TRUE;
$Configuration['EnabledPlugins']['embedvanilla'] = TRUE;
$Configuration['EnabledPlugins']['cleditor'] = TRUE;
$Configuration['EnabledPlugins']['WhosOnline'] = TRUE;
$Configuration['EnabledPlugins']['EMailDiscussion'] = TRUE;
$Configuration['EnabledPlugins']['ImageUpload'] = TRUE;
$Configuration['EnabledPlugins']['LikeThis'] = TRUE;
$Configuration['EnabledPlugins']['KarmaBank'] = TRUE;
$Configuration['EnabledPlugins']['MobileSearch'] = TRUE;
$Configuration['EnabledPlugins']['MembersListEnh'] = TRUE;
$Configuration['EnabledPlugins']['KarmaLeaderBoard'] = TRUE;
$Configuration['EnabledPlugins']['Mediator'] = TRUE;
$Configuration['EnabledPlugins']['MentionsLookup'] = TRUE;
$Configuration['EnabledPlugins']['Minify'] = TRUE;
$Configuration['EnabledPlugins']['Tagging'] = TRUE;

// Garden
$Configuration['Garden']['Title'] = 'FEG Forum';
$Configuration['Garden']['Cookie']['Salt'] = 'U5Q5CRH31I';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Cookie']['Name'] = 'SOMETHING';
$Configuration['Garden']['Registration']['ConfirmEmail'] = FALSE;
$Configuration['Garden']['Registration']['Method'] = 'Connect';
$Configuration['Garden']['Registration']['CaptchaPrivateKey'] = '';
$Configuration['Garden']['Registration']['CaptchaPublicKey'] = '';
$Configuration['Garden']['Registration']['InviteExpiration'] = '-1 week';
$Configuration['Garden']['Registration']['ConfirmEmailRole'] = '3';
$Configuration['Garden']['Registration']['InviteRoles'] = 'a:5:{i:3;s:1:"0";i:4;s:1:"0";i:8;s:1:"0";i:32;s:1:"0";i:16;s:1:"0";}';
$Configuration['Garden']['Email']['SupportName'] = 'FEG Forum';
$Configuration['Garden']['Version'] = '2.0.18.8';
$Configuration['Garden']['RewriteUrls'] = TRUE;
$Configuration['Garden']['CanProcessImages'] = TRUE;
$Configuration['Garden']['Installed'] = TRUE;
$Configuration['Garden']['Theme'] = 'EmbedFriendly';
$Configuration['Garden']['InstallationID'] = 'FDAF-2A2B9EDC-99938681';
$Configuration['Garden']['InstallationSecret'] = 'b6f6de60da3c2fe688373144289785736bd48ef1';
$Configuration['Garden']['Format']['Hashtags'] = FALSE;

// Modules
$Configuration['Modules']['Vanilla']['Content'] = 'a:6:{i:0;s:13:"MessageModule";i:1;s:7:"Notices";i:2;s:21:"NewConversationModule";i:3;s:19:"NewDiscussionModule";i:4;s:7:"Content";i:5;s:3:"Ads";}';
$Configuration['Modules']['Conversations']['Content'] = 'a:6:{i:0;s:13:"MessageModule";i:1;s:7:"Notices";i:2;s:21:"NewConversationModule";i:3;s:19:"NewDiscussionModule";i:4;s:7:"Content";i:5;s:3:"Ads";}';

// Plugins
$Configuration['Plugins']['GettingStarted']['Dashboard'] = '1';
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';
$Configuration['Plugins']['GettingStarted']['Categories'] = '1';
$Configuration['Plugins']['GettingStarted']['Discussion'] = '1';
$Configuration['Plugins']['GettingStarted']['Registration'] = '1';
$Configuration['Plugins']['GettingStarted']['Profile'] = '1';
$Configuration['Plugins']['EmbedVanilla']['RemoteUrl'] = 'http://live.fegllc.com/forum';
$Configuration['Plugins']['UploadImage']['Multi'] = TRUE;
$Configuration['Plugins']['UploadImage']['MaxHeight'] = '';
$Configuration['Plugins']['UploadImage']['MaxWidth'] = 650;
$Configuration['Plugins']['UploadImage']['MaxFileSize'] = '25mb';
$Configuration['Plugins']['KarmaBank']['Version'] = '0.9.6.9b';
$Configuration['Plugins']['KarmaBank']['Enabled'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['DCount'] = '100';
$Configuration['Plugins']['MembersListEnh']['ShowPhoto'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowSymbol'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowPeregrineReactions'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowLike'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowThank'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowKarma'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowAnswers'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowID'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowRoles'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowFVisit'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowLVisit'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowEmail'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowIP'] = FALSE;
$Configuration['Plugins']['MembersListEnh']['ShowVisits'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowDiCount'] = '1';
$Configuration['Plugins']['MembersListEnh']['ShowCoCount'] = '1';

// Routes
$Configuration['Routes']['DefaultController'] = 'discussions';
$Configuration['Routes']['Xm1lbWJlcnMoLy4qKT8k'] = 'a:2:{i:0;s:23:"plugin/MembersListEnh$1";i:1;s:8:"Internal";}';

// Vanilla
$Configuration['Vanilla']['Version'] = '2.0.18.8';

// WhosOnline
$Configuration['WhosOnline']['Frequency'] = '600';
$Configuration['WhosOnline']['Location']['Show'] = 'every';
$Configuration['WhosOnline']['Hide'] = FALSE;

// Last edited by MichaelHer (71.13.156.113)2014-07-09 09:13:01