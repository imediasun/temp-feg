<?php if (!defined('APPLICATION')) exit();

define("SPACER_WIDTH", 0);

/**
 * Show all Users on a map
 *
 */
class UserMapModule extends Gdn_Module {
   /**
    *  AssetTarget decides where to show the modul
    *  
    */
   public function AssetTarget() {
      return 'Panel';
   } // End of AssetTarget

   /**
    *  GetData fetches longitude, latitude information from database
    *  
    *  @return array   'Longitude', 'Latitude', 'UserCount' for each user
    */
   public function GetData() {
      return UserMapModel::Get();
   } // End of GetData
   
   /**
    *  DrawMap creates image which is shown in module
    *  
    *  @param object
    */
   private function DrawMap($LongLatList) {
      // Use PHP implementation of Modest Maps for showing Map
      require_once PATH_PLUGINS.DS.'UserMap'.DS.'lib'.DS.'modestmaps'.DS.'ModestMaps.php';
      
      // get images from MapQuest
      $MMProvider = new MMaps_Templated_Spherical_Mercator_Provider('http://otile1.mqcdn.com/tiles/1.0.0/map/{Z}/{X}/{Y}.png');
      
      // get min and max coordinates for drawing the map
      $MapInfo = UserMapModel::GetMapInfo();
      
      // define how much more of the map is shown than the upper and lower most user
      $MinLatitude = $MapInfo->MinLat - SPACER_WIDTH;
      $MaxLatitude = $MapInfo->MaxLat + SPACER_WIDTH;
      $MinLongitude = $MapInfo->MinLong - SPACER_WIDTH;
      $MaxLongitude = $MapInfo->MaxLong + SPACER_WIDTH;
      
      $Min = new MMaps_Location($MinLatitude, $MinLongitude);
      $Max = new MMaps_Location($MaxLatitude, $MaxLongitude);
      $Dim = new MMaps_Point(230, 230);
      $MMMap = MMaps_mapByExtent(new MMaps_OpenStreetMap_Provider(), $Min, $Max, $Dim);

      $MMImage = $MMMap->draw();

      foreach ($LongLatList as $UserLocation) {
         // calculate x/y values from long/lat
         $MMPoint = $MMMap->locationPoint(new MMaps_Location($UserLocation['Latitude'], $UserLocation['Longitude']));

         // calculate dot size from count of users from that location id
         // $DotSize = 4 + 2 * $UserLocation['UserCount'];
         $DotSize = 6 + $UserLocation['UserCount'];
         imagefilledellipse($MMImage, $MMPoint->x, $MMPoint->y, $DotSize, $DotSize, imagecolorallocate($MMImage, 250, 0, 0));
      }

      // and save it as an image
      imagejpeg($MMImage, PATH_PLUGINS.DS.'UserMap'.DS.'design'.DS.'usermap.jpg');

   } // End of DrawMap   
   
   /**
    *  ToString prints the html to the target given in 
    *  function AssetTarget ('Panel' is the target here)
    *  
    */
   public function ToString() {
      $imagefile = PATH_PLUGINS.DS.'UserMap'.DS.'design'.DS.'usermap.jpg';
      // check if file already exists and only redraw if it is too old
      if (!file_exists($imagefile) OR (time() - filectime($imagefile) > C('Plugins.UserMap.UpdateInterval'))) {
         // get geo ip information
         $LongLatList = $this->GetData();
         // create the picture
         $this->DrawMap($LongLatList);
      }
?>
<div class="Box">
   <h4><?php echo T('User Map'); ?></h4>
   <div id="UserMapContainer">
      <img src="/plugins/UserMap/design/usermap.jpg" alt="usermap" />
   </div>
</div>
<?php
   } // End of ToString
}


