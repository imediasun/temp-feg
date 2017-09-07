<?php if (!defined('APPLICATION')) exit();

$PluginInfo['EMailDiscussion'] = array(
   'Description' => 'Allows users that have certain permissions to mark a discussion as an announcement and have it sent to everybody',
   'Version' => '0.1',
   'RequiredApplications' => NULL,
   'RequiredTheme' => FALSE,
   'RequiredPlugins' => FALSE,
   'RegisterPermissions' => array('Plugins.EMailNotification.Send'),
   'HasLocale' => FALSE,
   'Author' => "Catalin David",
   'AuthorEmail' => 'c.david@jacobs-university.de'
);

class EMailDiscussion extends Gdn_Plugin {

	public function PostController_BeforeFormButtons_Handler(&$Sender) {
		$Session = Gdn::Session();
		if($Session->CheckPermission('Plugins.EMailNotification.Send')) {
			if (in_array($Sender->RequestMethod, array('discussion', 'editdiscussion'))) {
				$FormValues = $Sender->EventArguments['FormPostValues'];
				echo "<ul class='PostOptions'><li>";
				echo $Sender->Form->CheckBox('EMailDiscussion', T('E-mail ALL Users About This Discussion (reserve for very important notices) **MAY TAKE A FEW MINUTES TO FINISH SENDING - DO NOT RESEND OR REFRESH UNTIL PAGE TIMES OUT**'), array('value' => '1'));
				echo "</li></ul>";
			}
		}
	}

	public function PostController_AfterDiscussionSave_Handler(&$Sender) {
		$EMailCheckBox = $Sender->Form->GetFormValue('EMailDiscussion', '');
		$DiscussionID = $Sender->EventArguments['Discussion']->DiscussionID;
		$DiscussionName = $Sender->EventArguments['Discussion']->Name;
		$Story = "Entitled..." . $Sender->EventArguments['Discussion']->Name;
		$SQL = Gdn::SQL();
		if($EMailCheckBox == '1') {
			//send an e-mail to everybody
			//1) select all users' e-mail address
			$SqlEmails = $SQL->Select('EMail','','EMail')
			->From('User')
			->Get();
			$Emails = array();
			while($Email = $SqlEmails->NextRow(DATASET_TYPE_ARRAY)) {
				$Emails[] = $Email['EMail'];
			}
				
			//2) get current user
			$Session = Gdn::Session();
			$Name = $Session->User->Name;

			//3) mail
			//bool mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] )
			$Subject =  "FEG FORUM - An Important Message from ".$Name." about '".$DiscussionName."'!" ;

			$Message = '
			<html>
				<head>
					<title>'.$Subject.'</title>
				</head>
				<body>
					<p>'.$Name.' has posted an important message about "<b>'.$DiscussionName.'</b>" in the FEG Forum!
						<br>
						<br> 
						<a style="font-size:1.1em;" href="http://admin.fegllc.com" target="_blank">Login to FEG.com</a>
						<br>
						<a style="font-size:1.3em;" href="http://admin.fegllc.com/forum#/discussion/'.$DiscussionID.'" target="_blank">Go Straight to Message</a> (must be logged in)
						<br>
						<br>
						Thank you!
					</p>  
				</body>
			</html>
			';
				
			$Headers =  'MIME-Version: 1.0' . "\r\n".
						'Content-type: text/html; charset=iso-8859-1' . "\r\n".
						'From: support@fegllc.com' . "\r\n" .
						'Reply-To: ' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
			
			foreach($Emails as $Email) {
				mail($Email, $Subject, $Message, $Headers);
			}
		}
	}

	public function Setup() {
		$SQL = Gdn::SQL();
		$Database =  Gdn::Database();
		
		$PermissionModel = Gdn::PermissionModel();
		$PermissionModel->Database = $Database;
		$PermissionModel->SQL = $SQL;

		// Define some global addon permissions.
		$PermissionModel->Define(array(
         'Plugins.EMailNotification.Send'
      ));

      // Set the initial administrator permissions.
      $PermissionModel->Save(array(
         'RoleID' => 16,
         'Plugins.EMailNotification.Send' => 1
      ));

      // Make sure that User.Permissions is blank so new permissions for users get applied.
      $SQL->Update('User', array('Permissions' => ''))->Put();
	}
}
