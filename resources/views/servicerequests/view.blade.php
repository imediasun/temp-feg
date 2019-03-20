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

                    @if(!empty($row->shipping_priroty))
                        <span class="ticketCreatedOnText hasPrefixSeparator">Shipping Priority: {{$row->shipping_priroty}}</span>
                    @endif
                </div>

            </div>
        </div>

        <div class="sbox-content">
            @endif
            <div class="ticketViewParentContainer clearfix">
                {!! Form::open(array('url'=>'servicerequests/status-update/'.SiteHelpers::encryptID($row['TicketID']), 'id'=> 'servicerequestsStatusUpdateFormAjax')) !!}
                {!! Form::hidden('TicketID', $row['TicketID'],['id'=>'TicketID']) !!}
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
                        @if($ticketType == 'game-related' &&  $row->issue_type_id != \App\Models\Servicerequests::PART_APPROVAL)
                        <div class="followersListContainer sidebarInput" style="border: 1px solid black;">
                            <div style="font-size: 15px; font-weight: 700; text-align: center; margin-bottom: 5px; padding: 5px 0px;">Troubleshooting Checklist</div>
                            <div>
                                @foreach($troubleshootingCheckLists as $troubleshootingCheckList)
                                    <div style="font-size:6px; padding:5px;">

                                        <input type="checkbox" disabled name="troubleshootchecklist[]" @if(in_array($troubleshootingCheckList->id,$savedCheckList['savedCheckList'])) checked @endif id="troubleshootchecklist_{{ $troubleshootingCheckList->id  }}" value="{{ $troubleshootingCheckList->id }}">&nbsp;&nbsp;
                                        <label title="{{ !empty($savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]) ? $savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]:$troubleshootingCheckList->check_list_name }}" class="tips" style="vertical-align: middle; width: 85%; font-size: 12px; white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;" for="troubleshootchecklist_{{ $troubleshootingCheckList->id  }}">{{ !empty($savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]) ? $savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]:$troubleshootingCheckList->check_list_name }}</label>
                                    </div>
                                @endforeach
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
                                    <div id="part-requests-contianer" style="margin-top: -30px;" class="form-group  ">
                                        <div class="col-md-12" style="padding-left: 0px;"><label class="replyLabel">Part
                                                Information</label></div>
                                        <div class="col-md-12 part-request-inner">
                                            <div class="row">
                                            <div class="row" style="margin-left: 15px; margin-right: 15px">
                                                <div class="col-md-3 part-number-container"><label >Part Number</label></div>
                                                <div class="col-md-3 part-number-container"><label >Quantity</label></div>
                                                <div class="col-md-3 part-number-container"><label >Cost</label></div>
                                                <div class="col-md-3 part-number-container" align="center"><label>Action</label></div>
                                            </div>
                                            </div>
                                            <div class="row">
                                                <dev class="part-request-field-contianer"
                                                     id="part-request-field-contianer">
                                                    <input type="hidden" id="part_request_removed"
                                                           style="display: none;">
                                                    <?php $i = 1; ?>
                                                    @if($partRequests->count()>0)
                                                        @foreach($partRequests as $partRequest)
                                                            <div class="part-request-field row"
                                                                 id="part-request-field_{{ $i }}"
                                                                 style="margin-left: 15px; margin-right: 15px">
                                                                <div class="col-md-3 part-number-container"
                                                                     style="margin-bottom: 20px;">

                                                                    <input type="text" class="form-control fixonfocus"
                                                                           @if(in_array($partRequest->status_id,\App\Models\PartRequest::STATUS_IDS)) readonly
                                                                           @endif
                                                                           value="{{ $partRequest->part_number }}"
                                                                           id="part-number-{{ $i }}" name="part_number">
                                                                </div>
                                                                <div class="col-md-3 part-qty-container"
                                                                     style="margin-bottom: 20px;">
                                                                    <input type="number" name="qty"
                                                                           @if(in_array($partRequest->status_id,\App\Models\PartRequest::STATUS_IDS)) readonly
                                                                           @endif
                                                                           id="part-qty-{{ $i }}"
                                                                           value="{{ $partRequest->qty }}"
                                                                           class="form-control fixonfocus">
                                                                </div>
                                                                <div class="col-md-3 part-cost-container part-request-last-field"
                                                                     style="margin-bottom: 20px; position: relative;">
                                                                    <div class="input-group ig-full">
                                <span class="input-group-addon"
                                      style="border-right: 1px solid #e5e6e7; position: absolute; left: 0;    z-index: 111111;">$</span>
                                                                        <input type="number" step="1"
                                                                               @if(in_array($partRequest->status_id,\App\Models\PartRequest::STATUS_IDS)) readonly
                                                                               @endif
                                                                               placeholder="0.00"
                                                                               value="{{ CurrencyHelpers::formatPrice($partRequest->cost,5,false ) }}"
                                                                               style="padding-left: 35px;"
                                                                               id="part-cost-{{ $i }}" name="cost[]"
                                                                               class="form-control fixonfocus">
                                                                    </div>
                                                                    <i class="fa fa-times tips remove-part-request-fields" id="remove-part-request-fields1" remove-id="{{ $partRequest->id }}" title="" onclick="removePartRequest('part-request-field_1','{{ $partRequest->id }}');" style="position: absolute; cursor: pointer; top: 7px; font-size: 18px; color: #e00f0f; right:0px;" data-original-title="Remove"></i>
                                                                </div>
                                                                <div class="col-md-3"
                                                                     style="margin-bottom: 20px; text-align: center; padding-left: 0px; ">
                                                                    <div class="action-btns">
                                                                        @if(in_array($partRequest->status_id,\App\Models\PartRequest::STATUS_IDS))

                                                                            @if($partRequest->status_id == 2)
                                                                                <span style="background: #3c763d; color:white; font-weight: 700; padding: 2px 5px;">Approved</span>

                                                                            @else
                                                                                <span style="background-color: #ed5565; color:white; font-weight: 700; padding: 2px 5px;">Denied</span>

                                                                            @endif


                                                                        @else
                                                                            <a href="#"
                                                                               onclick="savePartRequest('{{ $i }}',this,'{{ $partRequest->id }}'); return false;"
                                                                               class="btn btn-primary tips"
                                                                               title="Save"
                                                                               style="margin-left: 3px;"><i
                                                                                        class="fa fa-save"></i></a>
                                                                            @if($can_approve_deny == true)
                                                                                <a href="#"
                                                                                   onclick="approvePartRequest('{{ $partRequest->id }}','{{ $i }}'); return false;"
                                                                                   class="btn btn-primary greenbutton tips"
                                                                                   title="Approve"
                                                                                   style="margin-left: 3px;"><i
                                                                                            class="fa fa-check"></i></a>
                                                                                <a href="#"
                                                                                   onclick="denyPartRequest('{{ $i }}','{{ $partRequest->id }}',this); return false;"
                                                                                   class="btn btn-warning redbutton tips"
                                                                                   title="Deny"
                                                                                   style="margin-left: 3px;"><i
                                                                                            class="fa fa-ban"></i></a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div style="clear: both;"></div>
                                                                @if($partRequest->status_id == 3)
                                                                    @if(!empty($partRequest->reason))
                                                                        <div class="col-md-9 reasontxt"
                                                                             style="margin-bottom: 20px; margin-top:-15px;">
                                                                            <span style=" color:red;"><b>Reason: &nbsp;</b>{{ $partRequest->reason }}</span>
                                                                        </div>
                                                                        <div class="col-md-3 reasontxt"></div>
                                                                    @endif
                                                                @endif
                                                                <div class="col-md-9"></div>
                                                                <div class="col-md-3"></div>


                                                            </div>
                                                            <?php $i++; ?>
                                                        @endforeach
                                                    @else
                                                        <div class="part-request-field" id="part-request-field_1">
                                                            <div class="col-md-3 part-number-container"
                                                                 style="margin-bottom: 20px;">
                                                                <input type="text" class="form-control fixonfocus"
                                                                       name="part_number"
                                                                       id="part-number-1">
                                                            </div>
                                                            <div class="col-md-3 part-qty-container"
                                                                 style="margin-bottom: 20px;">
                                                                <input type="number" name="qty"
                                                                       class="form-control fixonfocus"
                                                                       id="part-qty-1">
                                                            </div>
                                                            <div class="col-md-3 part-cost-container part-request-last-field"
                                                                 style="margin-bottom: 20px; position: relative;">
                                                                <div class="input-group ig-full">
                                <span class="input-group-addon"
                                      style="border-right: 1px solid #e5e6e7; position: absolute; left: 0;    z-index: 111111;">$</span>
                                                                    <input type="number" step="1" placeholder="0.00"
                                                                           value=""
                                                                           style="padding-left: 35px;" name="cost"
                                                                           class="form-control fixonfocus"
                                                                           id="part-cost-1">
                                                                </div>
                                                                <i class="fa fa-times tips remove-part-request-fields" id="remove-part-request-fields1" title="" onclick="removePartRequest('part-request-field_1');" style="position: absolute; cursor: pointer; top: 7px; font-size: 18px; color: #e00f0f; right:0px;" data-original-title="Remove"></i>

                                                            </div>
                                                            <div class="col-md-3"
                                                                 style="margin-bottom: 20px; text-align: center; padding-left: 0px; ">
                                                                <div class="action-btns">

                                                                    <a href="#"
                                                                       onclick="savePartRequest('1',this,'0'); return false;"
                                                                       class="btn btn-primary  tips"
                                                                       title="Save"
                                                                       style="margin-left: 3px;"><i
                                                                                class="fa fa-save"></i></a>
                                                                </div>
                                                            </div>
                                                            <div style="clear: both;"></div>

                                                        </div>
                                                    @endif

                                                </dev>
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
                                                        unset($statusOptions['inqueue']);
                                                    }elseif($ticketType == 'debit-card-related'){
                                                        unset($statusOptions['in_process']);
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
