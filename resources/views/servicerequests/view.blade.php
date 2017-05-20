{{--*/      use App\Library\FEG\System\FEGSystemHelper;                   /*--}}
<style>
    .collapse-close
    {
        top:0 !important;
    }
</style>
@if($setting['view-method'] =='native')
	<div class="sbox">
		<div class="ticketHeaderContainer clearfix">
            <div class="closeButtonContainer">
				<a href="javascript:void(0)" 
                   class="collapse-close pull-right btn btn-xs btn-danger closeViewButton" 
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </div>
            <div class="headerThumbnail tips" data-ticket-type="{{ $row->issue_type }}" 
                 data-toggle="tooltip" data-placement="top" 
                title="{{ $row->issue_type }}" >{{ substr($row->issue_type, 0, 1) }}</div>
            <div class="headerTextsContainer clearfix" >
                <div class="clearfix headerTopText" >
                    <span class="ticketIdText">{{ $ticketID }}</span>
                    <span class="ticketLocationText label label-success">{{ $locationName }}</span>
                    <span class="ticketNeededByText label label-warning">Needed by {{$dateNeeded}}</span>                    
                    <span class="ticketStatusText label label-muted" data-ticket-status="{{ $ticketStatus }}">{{ $ticketStatusLabel }}</span>
                </div>
                <div class="clearfix headerTitleContainer">
                    <h2>
                        <span class="headerTitleText">{{ $row->Subject }}</span>                        
                    </h2>
                </div>
                <div class="clearfix headerMetaText">
                    <span class="ticketCreatorText tips"
                           data-toggle="tooltip" data-placement="top" 
                            title="{{ $creatorTooltip }}" 
                          >{{ $creatorName }}</span>
                    <span class="ticketCreatedOnText hasPrefixSeparator">Created on {{ $createdOnWithTime }}</span>
                    @if (!empty($updatedOnWithTime))
                    <span class="ticketUpdatedOnText hasPrefixSeparator">Last updated on {{ $updatedOnWithTime }}</span>
                    @endif
                </div>

            </div>
        </div>
        
		<div class="sbox-content">
@endif
			<div class="ticketViewParentContainer clearfix">
                {!! Form::open(array('url'=>'servicerequests/status-update/'.SiteHelpers::encryptID($row['TicketID']), 'id'=> 'servicerequestsStatusUpdateFormAjax')) !!}
                    {!! Form::hidden('TicketID', $row['TicketID']) !!}
                    {!! Form::hidden('Status', $row['Status']) !!}
                    {!! Form::hidden('oldStatus', $row['Status']) !!}
                {!! Form::close() !!}
                {!! Form::open(array('url'=>'servicerequests/comment/'.SiteHelpers::encryptID($row['TicketID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'servicerequestsFormAjax')) !!}
                <div class="ticketLeftSidebarContainer" >
                    <div class="ticketLeftSidebar">
                        <!--<h2 class="sidebarTicketIDText sidebarText" data-caption="Ticket ID: #">{{$ticketID}}</h2>-->
                        <div class="clearfix">
                        <div class="followControlContainer">
                            <input name="isFollowingTicket"
                                data-size="mini" data-animate="true" 
                                data-on-text="Subscribed" 
                                data-off-text="Not Subscribed" 
                                data-handle-width="100px"
                                class="isFollowing" type="checkbox" 
                                @if($following) checked @endif />
                        </div>
                        </div>
                        <div class="attachmentContainer sidebarInput" 
                            data-caption="Attachments">
                            <div class="text-center">
                            <button class="btn btn-primary addAttachmentField"
                                    data-container=".attachmentInputs"
                               >Attach file</button>
                            <div class="attachmentInputs">                                
                            </div>                                
                            </div>
                            <div class="previousAttachmentList">
                            @foreach ($comments as $comment)
                                @include('servicerequests.ticketattachmentitem', [
                                    'data' => $comment, 
                                    'isInitialTicket' => false, 
                                    'userProfile' => FEGSystemHelper::getTicketCommentUserProfile($comment)
                                ])
                            @endforeach
                            @include('servicerequests.ticketattachmentitem', [
                                    'data' => $row, 
                                    'isInitialTicket' => true, 
                                    'userProfile' => $creatorProfile
                                ])
                            </div>                                
                        </div>
                        <div class="followersListContainer sidebarInput" data-caption="Followers">
                                <select name='allFollowers[]' multiple id='followers' class='select2 ' ></select>
                        </div>

                    </div>
                </div>
                <div class="ticketMainViewContainer">
                    <div class="ticketHeaderAddonsContainer"></div>
                    <div class="ticketReplyContainer">
                        <div class="myUserProfileImageContainer img-avatar-container tips"
                                data-toggle="tooltip" data-placement="top" 
                                title="{{ $myUserTooltip }}"
                                 >
                                <img alt="" src="{{  $myUserAvatar }}" width="45" 
                                     class="img-avatar img-circle tips" border="0"/>
                        </div>                        
                        <div class="ticketReplyInputsContainer" >
                            <div class="ticketReplyMainContainer" >
                                <div class="replyLabel">Reply</div>
                                <div class="ticketReplyFieldContainer">
                                    <textarea name='Comments' rows='5' 
                                        id='Comments' class='form-control ' 
                                            required  ></textarea>                                
                                    <div class="toolControls form-inline">
                                        <button type="submit" 
                                                class="btn btn-primary btn-sm pull-right submitButton"
                                                ><i class="fa  fa-save "></i> Update</button>
                                        @if ($canChangeStatus) 
                                        <div class="selectStatusDropdownContainer">
                                            <input type='hidden' name='oldStatus' value='{{ $ticketStatus }}' />
                                            <select name='Status' required class='Status '>
                                                @foreach($statusOptions as $key => $val)
                                                    <option  value ='{{ $key }}' 
                                                        @if($ticketStatus == $key) selected='selected' @endif
                                                    >{{ $val }}</option>";
                                                @endforeach
                                            </select>                                            
                                        </div>
                                        @endif
                                        <div class="selectPriorityDropdownContainer">
                                            <select name='Priority' required class='Priority '>
                                                @foreach($priorityOptions as $key => $val)
                                                    <option  value ='{{ $key }}' 
                                                        @if($row['Priority'] == $key) selected='selected' @endif
                                                    >{{ $val }}</option>";
                                                @endforeach
                                            </select>                                            
                                        </div>                                        
                                        
                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </div>
                    <div class="ticketCommentsContainer clearfix">
                        <div class="ticketCommentsHeaderContainer " >
                            <span class="">Comments</span>
                            <span class="badge tips" 
                                  data-toggle="tooltip" data-placement="top" 
                                title="{{ $conversationCount }} comments"
                                  >{{$conversationCount}}</span>
                        </div>                        
                        {{--*/ $commentsCountIndex = $commentsCount /*--}}
                        @foreach ($comments as $comment)                            
                            @include('servicerequests.commentview', [
                                'comment' => html_entity_decode(nl2br($comment->Comments)), 
                                'postedOn' => DateHelpers::formatDateCustom($comment->Posted), 
                                'commentIndex' => $commentsCountIndex, 
                                'commentIndexText' => 'REPLY #'.$commentsCountIndex, 
                                'userProfile' => FEGSystemHelper::getTicketCommentUserProfile($comment),
                            ])
                            {{--*/ $commentsCountIndex-- /*--}}
                        @endforeach
                        
                        @include('servicerequests.commentview', [
                                'comment' => html_entity_decode(nl2br($row->Description)), 
                                'postedOn' => $createdOnWithTime, 
                                'commentIndex' => 0, 
                                'commentIndexText' => 'INITIAL REQUEST', 
                                'userProfile' => $creatorProfile,
                            ])
                    </div>
                    <div class="ticketFooterContainer"></div>
                </div>
                {!! Form::hidden('UserID', $uid) !!}                
                {!! Form::hidden('TicketID', $row['TicketID']) !!}
                {!! Form::hidden('Subject', $row->Subject) !!}
                {!! Form::hidden('Description', $row->Description) !!}
                {!! Form::hidden('need_by_date', $row->need_by_date) !!}
                {!! Form::hidden('Created', $createdOn) !!}
                {!! Form::hidden('entry_by', $creatorID) !!}
                {!! Form::hidden('issue_type', $row->issue_type) !!}
                {!! Form::hidden('location_id', $row->location_id) !!}
                {!! Form::hidden('department_id', $row->department_id) !!}
                {!! Form::hidden('assign_to', $row->assign_to) !!}
                {!! Form::hidden('game_id', $row->game_id) !!}
                {!! Form::close() !!}
			</div>
			@if($setting['form-method'] =='native')
		</div>
	</div>
@endif
<script type="text/javascript">
    
    var mainUrl = '{{ $pageUrl }}',
        mainModule = '{{ $pageModule }}',
        ticketID = '{{ $ticketID }}',
        userId = '{{ $uid }}';
    
    $(document).ready(function() {
        App.modules.tickets.detailedView.init({
                'container': $('#'+pageModule+'View'),
                'moduleName': pageModule,
                'mainModule': mainModule,
                'url': pageUrl,
                'mainUrl': mainUrl,
            },
            { 
                'ticket': {!! json_encode($row) !!},
                'comments': {!! json_encode($comments) !!},
                'creator': {!! json_encode($creator) !!},
                'followers': {!! json_encode($followers) !!}
            }
        );
        
    });
</script>
