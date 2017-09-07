<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

// Define the plugin:
$PluginInfo['WhoReadConversation'] = array(
   'Name' => 'Who read the Conversation',
   'Description' => 'Displays a user list on the message showing the recipients who has already read it. You can see, if the recipients have read the message or not.',
   'Version' => '1.0',
   'Author' => "csakip"
);

class WhoReadConversationPlugin extends Gdn_Plugin {

	// Reverse the comment sort order
   public function MessagesController_BeforeConversationMessageBody_Handler($Sender) {
     $UserConversation = Gdn::SQL()->Select('uc.LastMessageID, uc.CountReadMessages, uc.DateLastViewed, u.Name, u.UserID')->From('UserConversation uc')->Join('User u', 'uc.UserId = u.UserId')->Where('ConversationID', $Sender->Conversation->ConversationID)->Get();
     $out = '';
     foreach($UserConversation as $conv){
       if($conv->DateLastViewed != null && $conv->DateLastViewed >= $Sender->EventArguments['Message']->DateInserted && $conv->UserID != $Sender->EventArguments['Message']->InsertUserID)
         $out=$out.$conv->Name.'  ';
     }
     if($out != '')
	     echo '<span class=\'Meta\' style=\'min-height:inherit\'>'.T('Read by: ').$out.'</span><br>';
   }

   public function OnDisable() {
   }
   public function Setup() {
   }
}