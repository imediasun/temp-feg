<?php 
    use App\Library\FEG\System\FEGSystemHelper;
    $attachments = FEGSystemHelper::getTicketAttachmentDetails($data, $isInitialTicket);
$commentAttachments = FEGSystemHelper::getCommentAttachmentDetails($data);
?>
@foreach($attachments as $attachment)
<div class="attachmentListItem">
    <a href="{{$attachment['url']}}"
       class="attachmentLink" target="_blank"
       >{{$attachment['fileName']}}</a>
    <div class="attachmentMeta">
         by <span class="attachmentCreator">{{ $userProfile['fullName'] }}</span>
         on <span class="attachmentDate">{{ $attachment['date'] }}</span>
    </div>
</div>
@endforeach
@if(count($commentAttachments)>0)
@foreach($commentAttachments as $commentAttachment)
    <div class="attachmentListItem">
        <a href="{{$commentAttachment['url']}}"
           class="attachmentLink" target="_blank"
        >{{$commentAttachment['fileName']}}</a>
        <div class="attachmentMeta">
            by <span class="attachmentCreator">{{ $commentAttachment['fullName'] }}</span>
            on <span class="attachmentDate">{{ $commentAttachment['date'] }}</span>
        </div>
    </div>
@endforeach
    @endif

