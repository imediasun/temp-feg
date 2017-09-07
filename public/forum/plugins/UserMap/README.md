# User Map  
    
This plugin shows a (static) map of all locations of the last active users. A look at the screenshot is the best explanation.   
     
By default only users who were active in the last 7 days are shown. This value can be changed in configuration file ($Configuration['Plugins']['UserMap']['ActiveDays']).  
The map is *not* refreshed at each page refresh, but earliest after two minutes. This value could also be configured ($Configuration['Plugins']['UserMap']['UpdateInterval']).   
    
More than one user at one location are shown by bigger markers.   
    
    
### The PlugIn GeoIPData is needed for this to work!
That plugin must be set up correctly, i. e. you must activate it and run through its setup.
   
The data used for getting the GeoIP information is provided by MaxMind. Thanks to them for giving away that info freely!   
   
The map is served by MapQuest and the display is done with ModestMaps. Thanks to them, too!   
    
