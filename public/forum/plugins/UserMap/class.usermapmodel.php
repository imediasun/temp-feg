<?php if (!defined('APPLICATION')) exit();

class UserMapModel extends Gdn_Model {
   /**
    *  Get returns an array containing longitude, latitude and count of each 
    *  ip address that is stored in user table in the column LastIPAddress
    *  but only for users that were active in the last 7 days
    *  
    *  @return     array   'Longitude', 'Latitude', 'UserCount' for each user
    */
   public function Get() {
      $days = C('Plugins.UserMap.ActiveDays');
      return Gdn::SQL()->Select('g.Longitude, g.Latitude, COUNT(g.LastIPAddress) UserCount')
         ->From('vw_GeoIPData g')
         ->LeftJoin('User u', 'u.LastIPAddress = g.LastIPAddress')
         ->Where('u.DateLastActive >=', 'NOW() - INTERVAL '.$days.' DAY', FALSE, FALSE)
         ->Where('u.Banned =', '0')
         ->Where('u.Deleted =', '0')
//         ->Where('UserId >=', '2')
         ->GroupBy('g.Longitude', 'g.Latitude')
         ->Get()
         ->Result(DATASET_TYPE_ARRAY);
   }

   /**
    *  GetLongitudeMinMax returns
    *  
    *  @return array    min and max of longitude and latitude
    */
   public function GetMapInfo() {
      $days = C('Plugins.UserMap.ActiveDays');
      return Gdn::SQL()->Select('MIN(g.Longitude) MinLong, MAX(g.Longitude) MaxLong, MIN(g.Latitude) MinLat, MAX(g.Latitude) MaxLat')
         ->From('vw_GeoIPData g')
         ->LeftJoin('User u', 'u.LastIPAddress = g.LastIPAddress')
         ->Where('u.DateLastActive >=', 'NOW() - INTERVAL '.$days.' DAY', FALSE, FALSE)
         ->Where('u.Banned =', '0')
         ->Where('u.Deleted =', '0')
//         ->Where('UserId >=', '2')
         ->Get()
         ->FirstRow();
   }
}