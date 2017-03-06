<?php 
    use App\Library\FEG\System\FEGSystemHelper;
    $attachments = FEGSystemHelper::getTicketAttachmentDetails($data, $isInitialTicket);
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

