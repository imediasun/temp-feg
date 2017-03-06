<?php 
    
    $commenterSpecialClass = '';
    if (isset($userProfile['isExternal']) && $userProfile['isExternal']) {
        $commenterSpecialClass = "notAnUser";
    }
    $userTooltip = $userProfile['tooltip'];
    $userFullName = $userProfile['fullName'];
    $userAvatar = $userProfile['avatar'];
?>
<div class="commentContainer @if($commentIndex==0) initialComment @endif" 
     data-comment-index='{{$commentIndexText}}' data-index='{{$commentIndex}}'>
    <div class="commentUserProfileImageContainer img-avatar-container tips"
         data-toggle="tooltip" data-placement="top" 
                    title="{{ $userTooltip }}"
         >
        <img alt="" src="{{$userAvatar}}" width="40"              
             class="img-avatar img-circle tips {{$commenterSpecialClass}}" 
             border="0"/>
    </div>
    <div class="commentMainContainer">
        <div class="commentMetaText">
            <span class="commentAuthorText tips {{$commenterSpecialClass}}"                  
                  >{{ $userFullName }}</span>
            <small>
                <span class="commentDateText">{{ $postedOn }}</span>
            </small>
        </div>
        <div class="commentText">
            {!! $comment !!}
        </div>            
    </div>
</div>
