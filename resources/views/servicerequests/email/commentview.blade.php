<?php 
    
    $userTooltip = $userProfile['tooltip'];
    $userFullName = $userProfile['fullName'];
    $userAvatar = $userProfile['avatar'];
    $notAnUser = false;
    if (isset($userProfile['isExternal']) && $userProfile['isExternal']) {
        $userFullName .= " <small>[Non-FEG User]</small>";
        $notAnUser = true;
    }
    // styles
    $commentContainer = "position: relative;
    padding: 30px 20px;
    margin: 5px;    
    background-color: #fff;";

    if($commentIndex!=0) {
        $commentContainer .= "border-bottom: 1px solid #eee;";
    }

    $commentAuthorText = "font-size: 14px;
    font-weight: bold;
    line-height: 20px;";

    $imgAvatar = "box-shadow: 0 0 1px 1px #d8d8d8; height: 45px;width: 45px;";
    
    if (!$notAnUser) {
        $commentAuthorText .= "color: #333;";
    }
    else {
        $commentAuthorText .= "color: #888;";
        $imgAvatar .= " opacity: .4;";
    }
    
    $commentUserProfileImageContainer = "position: absolute;";
    $imgAvatarContainer = "white-space: nowrap;";
    $imgCircle = "border-radius: 50%;";
    $commentMainContainer = "padding-left: 60px;";
    $commentMetaText = "margin: 0 0 10px 0;";
    $commentDateText = "    color: #aaa;
        font-weight: normal;
        font-size: 12px;
        line-height: 20px;
        margin-left: 5px;";
    $commentText = "color: #666;
        font-weight: normal;
        font-size: 14px;
        line-height: 1.456em;";
    $commentCountStyle = "color: #ddd; font-size: 10px; margin:-5px 0 10px 0;";

?>
<div style="{!! $commentContainer !!}">
    <div style="{!! $commentUserProfileImageContainer !!}{!! $imgAvatarContainer !!}"
                    title="{{ $userTooltip }}"
         >
        <img alt="" src="{{$userAvatar}}" width="40"              
             style="{!! $imgAvatar !!} {!! $imgCircle !!}"
             border="0"/>
    </div>
    <div style="{!! $commentMainContainer !!}">
        <p style="{!! $commentCountStyle !!}">{{$commentIndexText}}</p>
        <div style="{!! $commentMetaText !!}">
            <span style="{!! $commentAuthorText !!}"
                  >{!! $userFullName !!}</span>
            <small>
                <span style="{!! $commentDateText !!}">{{ $postedOn }}</span>
            </small>
        </div>
        <div style="{!! $commentText !!}">
            {!! $comment !!}
        </div>            
    </div>
</div>
