<?php
    $ticketCommentsHeaderContainer = "font-size: 16px;padding: 14px 11px;border-bottom: 1px solid #ddd;";
    $badge = "font-size: 14px;padding-left: 10px;padding-right: 10px;margin-left: 5px;   padding-top: 5px;";
?>
<div style="{!! $ticketCommentsHeaderContainer !!}" >
    <span style="">Comments</span>
    <span style="{!! $badge !!}"
          >{{ $conversationCount }} comments</span>
</div> 

