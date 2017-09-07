Thanks for using the Plugin.

Please go to the settings page in the dashboard for this plugin.
It is always a good idea to disable the plugin and then update to new version, and then re-enable, and check settings.

Please send me a donation if you found this plugin useful.  A donation button link is in the dashboard settings neat the bottom of the page



regards,

Peregrine.



in conf/config.php


A)  If you want to exclude ONE and only ONE Role, e.g. EITHER the Applicant or Confirm Mail or some Other Role  that show up in viewing page - you can enter the roleid in config.

    You can exclude only ONE ROLE. You can enter the roleid in config and change first value to "Exclude".

    If the member has multiple roles, they will not be excluded from the results in the view page.

    $Configuration['Plugins']['MembersListEnh']['RoleID'] =  array('Exclude', '3');


B)  If you want to include Only One Role e.g. Member  you can enter the roleid in config and change first value to "Include".
     You can include ONE ROLE.

    $Configuration['Plugins']['MembersListEnh']['RoleID'] =  array('Include', '8');

C)  If you want all roles in the roletable to show up in membership view page.  Delete the $Configuration['Plugins']['MembersListEnh']['RoleID']


******************************  IMPORTANT ****************************************************

For all of you who got a free plugin, but still want more free help.  Well, in lieu of free help:



Steps to ensuring a better response to any questions, in this order.

1.  make a donation
2.  ask question
3.  repeat as necessary.

if you like the plugin, but want a new feature or a modification.
1. send in your donation for the existing plugin.
2. specify what you want, and how much you will pay for the modification on the forum.


If you made a donation, I will try to answer your questions.  If your pledge amount for a new feature is something I can do based on the amount you want to pay, I will do it.



If you did not send me a donation for the existing plugin Please DO NOT ASK ME "How do I questions" or "How do I change this?"  or ask for "Feature Requests" for the plugins.

