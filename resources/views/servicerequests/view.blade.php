{{--*/      use App\Library\FEG\System\FEGSystemHelper;                   /*--}}
<style>
    .collapse-close
    {
        top:0 !important;
    }
    .margin-bottom-30px{
        margin-bottom: 30px;
    }
    .part-request-inner{
        background: #f9f9f9;
        padding-bottom: 20px;
        border: 1px solid #ececec;
    }
    .remove-part-request-fields{
        right: 0px !important;
        z-index: 1000;
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
                    @if($ticketType == 'game-related')
                    <span class="ticketLocationText label label-success">{{ $gameName }}</span>
                    <span class="ticketNeededByText label label-warning">{{ $row->issue_type }}</span>
                    <span class="ticketNeededByText label label-danger">{{$row->functionality}}</span>
                    @else
                    <span class="ticketNeededByText label label-warning">Needed by {{$dateNeeded}}</span>
                    @endif
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
                    @if(!empty($row['phone']))
                        <span class="ticketCreatedOnText hasPrefixSeparator">Requester's Phone Number: {{$row['phone']}}</span>
                    @endif
                </div>

            </div>
        </div>

        <div class="sbox-content">
            @endif
            <div class="ticketViewParentContainer clearfix">
                {!! Form::open(array('url'=>'servicerequests/status-update/'.SiteHelpers::encryptID($row['TicketID']), 'id'=> 'servicerequestsStatusUpdateFormAjax')) !!}
                {!! Form::hidden('TicketID', $row['TicketID']) !!}
                {!! Form::hidden('ticket_type', $ticketType) !!}
                {!! Form::hidden('Status', $row['Status']) !!}
                {!! Form::hidden('oldStatus', $row['Status']) !!}
                {!! Form::close() !!}
                {!! Form::open(array('url'=>'servicerequests/comment/'.SiteHelpers::encryptID($row['TicketID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'servicerequestsFormAjax')) !!}

                <div class="ticketLeftSidebarContainer col-sm-4 col-lg-3">
                    <div class="ticketLeftSidebar">
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
                        @if($ticketType == 'game-related')
                        <div class="followersListContainer sidebarInput" style="border: 1px solid black;">
                            <div style="font-size: 15px; font-weight: 700; text-align: center; margin-bottom: 5px; padding: 5px 0px;">Troubleshooting Checklist</div>
                            <div>
                                @foreach($troubleshootingCheckList as $itemChk)
                                    <div style="font-size:6px; padding:5px;">
                                        <input type="checkbox" disabled name="troubleshootchecklist[]" id="troubleshootchecklist_{{ $itemChk->id  }}" @if(in_array($itemChk->id,$savedCheckList)) checked @endif value="{{ $itemChk->id }}"> <label class="tips" style="vertical-align: middle; width: 90%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $itemChk->check_list_name }}" for="troubleshootchecklist_{{ $itemChk->id  }}">{{ $itemChk->check_list_name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="followersListContainer sidebarInput" style="border: 1px solid black;">
                            <div style="font-size: 15px; font-weight: 700; text-align: center; margin-bottom: 5px; padding: 5px 0px;">Part Information</div>
                            <div style="font-size:12px; text-align: left; ">
                                <div class="row" style="margin-bottom: 5px; margin-left: 0px; margin-right: 0px;">
                                    <div class="col-md-5" style="text-align: right; padding: 0px;">Part Number:&nbsp;&nbsp;</div>
                                    <div class="col-md-7" style="padding: 0px; padding-right: 2px;">
                                        <input type="text" style="width: 100%;" disabled value="{{ $row->part_number }}">
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 5px; margin-left: 0px; margin-right: 0px;">
                                    <div class="col-md-5" style="text-align: right;  padding: 0px;">Costs:&nbsp;&nbsp;</div>
                                    <div class="col-md-7" style="padding: 0px; padding-right: 2px;">
                                        <input type="text" style="width: 100%;" disabled value="{{ CurrencyHelpers::formatCurrency($row->cost) }}">
                                    </div>
                                    </div>
                                <div class="row" style="margin-bottom: 5px; margin-left: 0px; margin-right: 0px;">
                                    <div class="col-md-5" style="text-align: right; padding: 0px;">Quantity:&nbsp;&nbsp;</div>
                                    <div class="col-md-7" style="padding: 0px; padding-right: 2px;">
                                        <input type="text" style="width: 100%;" disabled value="{{ $row->qty }}">
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 5px; margin-left: 0px; margin-right: 0px;">
                                    <div class="col-md-5" style="text-align: right; padding: 0px;">Shipping Priority:&nbsp;&nbsp;</div>
                                    <div class="col-md-7" style="padding: 0px; padding-right: 2px;">
                                        <input type="text" style="width: 100%;" disabled value="{{ $row->shipping_priroty }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                            @endif
                    </div>
                </div>

                <div class="ticketMainViewContainer col-sm-8 col-lg-9">

                    <div class="ticketHeaderAddonsContainer"></div>
                    <div class="margin-bottom-30px">

                        <div class="row" style="    margin-right: 0px; margin-left: 0px;">

                            <div class="col-md-12">
                                <div id="part-requests-contianer" style="    margin-top: -30px;" class="form-group  ">
                                    <div class="col-md-12" style="padding-left: 0px;"><label class="replyLabel">Part Information</label></div>
                                    <div class="col-md-12 part-request-inner">
                                        <div class="row">
                                    <span class="part-request-field-contianer" id="part-request-field-contianer">
                                        <span class="part-request-field" id="part-request-field_1">
                                    <div class="col-md-3" style="margin-bottom: 20px;">
                                        <label for="part-number">Part Number</label>
                                        <input type="text" class="form-control" name="part_number[]" id="part-number">
                                    </div>
                                    <div class="col-md-3" style="margin-bottom: 20px;">
                                        <label for="part-number">Quantity</label>
                                        <input type="text" name="qty[]" class="form-control">
                                    </div>
                                    <div class="col-md-3 part-request-last-field"
                                         style="margin-bottom: 20px; position: relative;">
                                        <label for="part-number">Cost</label>
                                        <div class="input-group ig-full">
                                <span class="input-group-addon" style="border-right: 1px solid #e5e6e7;
    position: absolute;
    left: 0;    z-index: 111111;">$</span>
                                            <input type="number" step="1" placeholder="0.00" value=""
                                                   style="padding-left: 35px;" name="cost[]" class="form-control">
                                        </div>
                                        </div>
                                             <div class="col-md-3" style="margin-bottom: 20px; text-align: center; padding-left: 0px; ">
                                                <label >Action</label>
                                                 <div>

                                        <input type="button" value="Deny" class="btn btn-warning pull-right"  style="margin-left: 3px;">
                                        <input type="button" value="Approve" class="btn btn-primary pull-right" style="margin-left: 3px;">
                                                     <input type="button" value="Save" class="btn btn-primary pull-right">
                                                     </div>
                                    </div>

                                        </span>
                                    </span>
                                            <div class="col-md-12">
                                                <input type="button"
                                                       class="btn btn-primary pull-right addmorepartfields"
                                                       id="addmorepartfields" value="Add More">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
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


                                        @if ($canChangeStatus)
                                            <div class="selectStatusDropdownContainer col-md-4 col-sm-6">
                                                <input type='hidden' name='oldStatus' value='{{ $ticketStatus }}' />
                                                <select name='Status' required class='Status '>
                                                    <?php
                                                    if($ticketType == 'game-related'){
                                                        unset($statusOptions['development']);
                                                    }
                                                    ?>
                                                    @foreach($statusOptions as $key => $val)
                                                        <option  value ='{{ $key }}'
                                                                 @if($ticketStatus == $key) selected='selected' @endif
                                                        >{{ $val }}</option>";
                                                    @endforeach
                                                </select>
                                            </div>
                                    @endif

                                        <div class=" @if ($canChangeStatus) col-md-8 col-sm-12 @else col-md-12 col-sm-12 @endif ">
                                            <button type="submit" class="btn btn-primary btn-sm pull-right submitButton">
                                                <i class="fa  fa-save "></i> Update
                                            </button>
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
                            >{{$conversationCount}}
                            </span>

                        </div>

                        {{--*/ $commentsCountIndex = $commentsCount /*--}}
                        @foreach ($comments as $comment)
                            @include('servicerequests.commentview', [
                                'comment' => html_entity_decode(nl2br($comment->Comments)),
                                'postedOn' => DateHelpers::formatDateCustom($comment->Posted),
                                'commentIndex' => $commentsCountIndex,
                                'commentIndexText' => 'REPLY #'.$commentsCountIndex,
                                'userProfile' => FEGSystemHelper::getTicketCommentUserProfile($comment),
                                'commentClass' => $comment,
                            ])
                            {{--*/ $commentsCountIndex-- /*--}}
                        @endforeach

                        @include('servicerequests.commentview', [
                                'comment' => html_entity_decode(nl2br($row->Description)), 
                                'postedOn' => $createdOnWithTime, 
                                'commentIndex' => 0, 
                                'commentIndexText' => ($ticketType == 'game-related') ? 'TROUBLESHOOTING DESCRIPTION':'INITIAL REQUEST',
                                'userProfile' => $creatorProfile,
                                'commentClass' => $comments,
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
                {!! Form::hidden('ticket_type', $ticketType) !!}
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

        @if($ticketType == 'game-related')
        $('.ticketMainViewContainer').css("min-height",($('.ticketLeftSidebarContainer').height()-84)+"px");
        @endif

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
