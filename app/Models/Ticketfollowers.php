<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ticketsetting;
use App\Library\FEG\System\FEGSystemHelper;
use DB;

class Ticketfollowers extends Model {
	protected $table = 'sb_ticket_subscriptions';
    
    public static function follow($ticketId, $user, $custom = '', $force = false) {
        $users = [$user];
        if (is_string($user)) {
            $users = explode(',', $user);
        } 
        elseif (is_array($user)) {
            $users = $user;
        }
        foreach($users as $userId) {
            $id = self::where('ticket_id', $ticketId)->where('user_id', $userId)->pluck('id');
            if (is_null($id)) {
                self::insert([
                    'ticket_id' => $ticketId, 
                    'user_id' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'custom_email_to' => $custom,
                ]);
            }
            else {
                if ($force) {
                    self::where('id', $id)->update([
                        'updated_at' => date('Y-m-d H:i:s'),
                        'custom_email_to' => $custom,
                        'is_active' => 1,
                    ]);                    
                }
            }
        }        
    }
    public static function unfollow($ticketId, $user, $custom = '', $force = false) {
        $users = [$user];
        if (is_string($user)) {
            $users = explode(',', $user);
        } 
        elseif (is_array($user)) {
            $users = $user;
        }
        foreach($users as $userId) {
            $id = self::where('ticket_id', $ticketId)->where('user_id', $userId)->pluck('id');
            if (is_null($id)) {
                self::insert([
                    'ticket_id' => $ticketId, 
                    'user_id' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'custom_email_to' => $custom,
                    'is_active' => 0,
                ]);
            }
            else {
                if ($force) {
                    self::where('id', $id)->update([
                        'updated_at' => date('Y-m-d H:i:s'),
                        'custom_email_to' => $custom,
                        'is_active' => 0,
                    ]);                    
                }
            }
        }            
    }    
    public static function isFollowing($ticketId, $userId = null, $custom = '') {
        $isFollower = false;
        if (empty($userId)) {
            $userId = \Session::get('uid');
        } 
        $location = \App\Models\Servicerequests::where('TicketId', $ticketId)->pluck('location_id');
        if (!empty($location)) {
            $allFollowers = self::getAllFollowers($ticketId, $location);
            $isFollower = in_array($userId, $allFollowers);
        }        
        return $isFollower;
    }   
    
    public static function getAllFollowers($ticketId, $location = null, $includeNewTicketOnlyFollowers = false) {
        $default = self::getDefaultFollowers($location, $includeNewTicketOnlyFollowers);
        $others = self::getRecordedFollowersUnFollowers($ticketId);
        
        $recordedFollowers = $others['followers'];
        $recordedUnfollowers = $others['unfollowers'];
        $followers = array_unique(array_diff(array_merge($default, $recordedFollowers), $recordedUnfollowers));
        return $followers;
    }    
    public static function getRecordedFollowersUnFollowers($ticketId) {
        $followers = self::select('user_id', 'custom_email_to', 'is_active')
                ->where('ticket_id', $ticketId)->get();
        $followerUsers = [];
        $unfollowerUsers = ['', null];
        $followerCustomEmails = [];
        $unFollowerCustomEmails = ['', null];
        
        foreach($followers as $follower) {
            $isActive = $follower->is_active == 1;
            $user = $follower->user_id;
            $customEmail = $follower->custom_email_to;            
            if ($isActive) {
                if (!empty($user)) {
                    $followerUsers[] = $user;
                }
                if (!empty($customEmail)) {
                    $followerCustomEmails[] = $customEmail;
                }
            }
            else {
                if (!empty($user)) {
                    $unfollowerUsers[] = $user;
                }
                if (!empty($customEmail)) {
                    $unFollowerCustomEmails[] = $customEmail;
                }                
            }            
        }
        return [
            'followers' => $followerUsers,
            'unfollowers' => $unfollowerUsers,
            'customFollowers' => $followerCustomEmails,
            'customUnfollowers' => $unFollowerCustomEmails,
        ];        
    }        
    public static function getRecordedFollowers($ticketId) {
        $data = self::select('user_id', 'custom_email_to')
                ->where('ticket_id', $ticketId)
                ->where('is_active', 1)
                ->get();
        $followers = [];
        $followerCustomEmails = [];
        foreach($data as $item) {
            $user = $item->user_id;
            $customEmail = $item->custom_email_to;            
            if (!empty($user)) {
                $followers[] = $user;
            }
            if (!empty($customEmail)) {
                $followerCustomEmails[] = $customEmail;
            }
        }
        return $followers;
    }    
    public static function getRecordedUnFollowers($ticketId) {
        $data = self::select('user_id', 'custom_email_to')
                ->where('ticket_id', $ticketId)
                ->where('is_active', 0)
                ->get();
        $unfollowers = [];
        $unfollowerCustomEmails = [];
        foreach($data as $item) {
            $user = $item->user_id;
            $customEmail = $item->custom_email_to;            
            if (!empty($user)) {
                $unfollowers[] = $user;
            }
            if (!empty($customEmail)) {
                $unfollowerCustomEmails[] = $customEmail;
            }
        }
        return $followers;        
    }    
    public static function getDefaultFollowers($location = null, $includeNewTicketOnlyFollowers = false) {
        $settings = ticketsetting::getSettings();
        $userGroups  = $settings['role2'].','.$settings['role5'];
        $individuals = $settings['individual2'].','.$settings['individual5'];
        
        if ($includeNewTicketOnlyFollowers) {
            $userGroups  .= ','.$settings['role4'];
            $individuals .= ','.$settings['individual4'];
        }
        
        $groupUsers = FEGSystemHelper::getGroupsUserIds($userGroups, $location);
        $individualUsers = explode(',', $individuals);
        $users = array_diff(array_unique(array_merge($groupUsers, $individualUsers)), ['', null]);        
        
        return $users;        
    }    
}