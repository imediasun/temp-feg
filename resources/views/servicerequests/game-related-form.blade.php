{{--*/      use App\Library\FEG\System\FEGSystemHelper;                   /*--}}
<style>
    .form-group{
        position: static !important;
    }
    .form-horizontal .control-label{
        text-align: right;
    }
    .input-group span.input-group-addon {
        padding: 8px 18px 8px 10px;
    }
    .part-request-inner{
        background: #f9f9f9;
        padding-bottom: 20px;
        border: 1px solid #ececec;
    }
    @media screen and  (max-width:992px){
        .change-padding{
            padding-right: 15px !important;
            padding-left: 15px !important;
        }
    }
    @media screen and  (max-width:425px){
        .change-padding{
            padding-right: 15px !important;
            padding-left: 15px !important;
            font-size: 4px !important;
        }
    }
</style>
@if($setting['form-method'] =='native')
<div class="sbox">
    <div class="sbox-title">
        <h4>
            @if($id)
            <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Game-Related Service Request
            @else
            <i class="fa fa-plus"></i>&nbsp;&nbsp;Create Game-Related Service Request
            @endif
            <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
        </h4>
    </div>

    <div class="sbox-content">
        @endif
        {!! Form::open(array('url'=>'servicerequests/save-game-related/'.$row['TicketID'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'sbticketFormAjax')) !!}

        <input type="hidden" name='assign_to' value="{{ $row['assign_to']}}">
        <input type="hidden" name='entry_by' value="{{ $entryBy }}">
        @if($id)
            @else
        <div class="col-md-12 clearfix p-lg-f" style="color: blue; text-align: left; padding: 15px 30px 0px !important;">
            <a href="{{ url('servicerequests/update/?ticket_type=debit-card-related&close_return=game-related') }}"
               onclick="ajaxViewDetail('#servicerequests',this.href); return false;"
               class="tips"
               title="Create Debit Card-Related Service Request">Click Here</a> to create Debit Card-Related Service Request</div>
        @endif
        <div class="col-md-12 clearfix p-lg-f">
            <fieldset>
                <div class="row">
                    <div class="col-md-6">
                <div class="form-group  " text-align >
                    <label for="Location" class=" control-label col-md-4">
                        {!! SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
                    </label>
                    <div class="col-md-8">
                        <select name='location_id' rows='5' id='location_id' class='select2 ' required  ></select>
                    </div>

                </div>
                    </div>
                    <div class="col-md-6">
                <div class="form-group  " >
                    <label for="Game" class=" control-label col-md-3 text-left">
                        {!! SiteHelpers::activeLang('Game', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}
                    </label>
                    <div class="col-md-9">
                        <select name='game_id' rows='5' id='game_id' class='select2 ' required  ></select>
                    </div>
                </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group  " >
                            <label for="Issue Type" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Issue Type', (isset($fields['issue_type_id']['language'])? $fields['issue_type_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-8">
                                <select name='issue_type_id' rows='5' id="issue_type_id"  class='select2 ' required >
                                    <option value="">Select Issue Type</option>
                                    @foreach($game_related_issue_types as $gameRelatedIssueType)
                                        <option @if(!empty($row['issue_type_id'])) @if($row['issue_type_id'] == $gameRelatedIssueType->id) selected @endif @endif value ='{{ $gameRelatedIssueType->id }}'>{{ $gameRelatedIssueType->issue_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                <div class="form-group  " >
                    <label for="Functionality" class=" control-label col-md-3 text-left">
                        {!! SiteHelpers::activeLang('Functionality', (isset($fields['functionality_id']['language'])? $fields['functionality_id']['language'] : array())) !!}
                    </label>
                    <div class="col-md-9">
                        <select name='functionality_id' rows='5'   class='select2 ' required >
                            <option value="">Select Game Functionality</option>
                            @foreach($game_functionalities as $gameFunctionality)
                                <option @if(!empty($row['functionality_id'])) @if($row['functionality_id'] == $gameFunctionality->id) selected @endif @endif  value ='{{ $gameFunctionality->id }}'>{{ $gameFunctionality->functionalty_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    </div>
</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group  ">
                            <label for="Date" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Date', (isset($fields['game_realted_date']['language'])? $fields['game_realted_date']['language'] : array())) !!}
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" readonly required
                                       value="{{ !empty($row['game_realted_date']) ?  date('m / d / Y',strtotime($row['game_realted_date'])) : date('m / d / Y')}}"
                                       name="game_realted_date">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group  ">
                            <label for="Shipping Priority" class=" control-label col-md-3 text-left">
                                {!! SiteHelpers::activeLang('Shipping Priority', (isset($fields['shipping_priority_id']['language'])? $fields['shipping_priority_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-9">
                                <select name='shipping_priority_id' rows='5' id='shipping_priority_id' class='select2'>
                                    <option value="">--Select Shipping Priority--</option>
                                    @foreach($shippingPriorities as $shippingPriority)

                                        <option @if($shippingPriority->id == $row['shipping_priority_id']) selected
                                                @endif value="{{ $shippingPriority->id }}">{{ $shippingPriority->priority_name }}</option>

                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" >
                            <label for="Service Request Title" class="control-label col-md-2 text-left">
                                {!! SiteHelpers::activeLang('Service Request Title', (isset($fields['Subject']['language'])? $fields['Subject']['language'] : array())) !!}
                            </label>
                            <div class="col-md-10">
                                {!! Form::text('Subject', $row['Subject'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'required'  )) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group  " >
                            <label for="Description" class=" control-label col-md-2 text-left">
                                {!! SiteHelpers::activeLang('Troubleshooting Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}
                            </label>
                            <div class="col-md-10">
					  <textarea name='Description' rows='5' id='Description' class='form-control '
                                required  placeholder="Actions taken before opening game service or part approval request.">{{ $row['Description'] }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group clearfix" >
                            <label for="Attach File" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Attach File', (isset($fields['file_path']['language'])? $fields['file_path']['language'] : array())) !!}
                            </label>
                            <div class="col-md-8 col-sm-10 col-xs-12">
                                <div class="file_pathUpl">
                                    <input  type='file' name='file_path[]'  />
                                </div>
                                <a href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="addMoreFiles('file_path')"><i class="fa fa-plus"></i> Add more files</a>
                                <ul class="uploadedLists " >
                                    @foreach($filePaths as $cr => $file)
                                        @if($file !='')
                                            <li id="cr-{!! $cr !!}" class="">
                                                <a href="{{ url('.'.$file) }}" target="_blank" >{{  FEGSystemHelper::getSanitizedFileNameForTicketAttachments($file, 50) }}</a>
                                                <span class="pull-right" rel="cr-{!! $cr !!}" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
                                                <input type="hidden" name="currfile_path[]" value="{{ $file }}"/>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                <div id="trobleshotingchecklist-contianer" class="form-group  " @if($row['issue_type_id'] == \App\Models\Servicerequests::PART_APPROVAL) style="display: none;" @endif>
                    <label for="Description" class=" control-label col-md-2 text-left">
                        {!! SiteHelpers::activeLang('Troubleshooting Checklist', (isset($fields['troubleshootchecklist']['language'])? $fields['troubleshootchecklist']['language'] : array())) !!}
                    </label>

                    <div class="col-md-10" >
                        <div class="row">
                        <?php $index = 0 ?>
                        <div class="col-md-6">
                        @foreach($troubleshootingCheckLists as $troubleshootingCheckList)
                            <?php $index++ ?>
                            @if($index == 8)
                                </div>
                        <div class="col-md-6 change-padding" style="padding-left: 6%; padding-right: 0px;">
                                @endif
					<input type="checkbox" name="troubleshootchecklist[]" @if(in_array($troubleshootingCheckList->id,$savedCheckList['savedCheckList'])) checked @endif id="troubleshootchecklist_{{ $troubleshootingCheckList->id  }}" value="{{ $troubleshootingCheckList->id }}">&nbsp;&nbsp; <label title="{{ !empty($savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]) ? $savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]:$troubleshootingCheckList->check_list_name }}" class="tips" style="vertical-align: middle; width: 90%; font-size: 12px; white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;" for="troubleshootchecklist_{{ $troubleshootingCheckList->id  }}">{{ !empty($savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]) ? $savedCheckList['savedCheckListOptions'][$troubleshootingCheckList->id]:$troubleshootingCheckList->check_list_name }}</label><br /><br />
                        @endforeach
                        </div>
                        </div>
                    </div>
                </div>

                        <div id="part-requests-contianer" @if($row['issue_type_id'] == \App\Models\Servicerequests::PART_APPROVAL) style="display: block;" @else style="display: none;" @endif class="form-group  ">
                            <label  class=" control-label col-md-2 text-left">
                                Part Requests :
                            </label>
                            <div class="col-md-10 part-request-inner">
                                <div class="row">

                                            <span class="part-request-field-contianer"
                                                  id="part-request-field-contianer">
                                                <input type="hidden" name="part_request_removed" style="display: none;" id="part_request_removed">
                                                <?php $i = 1; ?>
                                                @if($partRequests->count()>0)
                                                    @foreach($partRequests as $partRequest)
                                        <span class="part-request-field" id="part-request-field_{{ $i }}">
                                    <div class="col-md-4" style="margin-bottom: 20px;">
                                            <input type="hidden" style="display: none;" name="part_request_id[]" class="part-request-id" id="partrequestid-{{ $i }}" value="{{ $partRequest->id }}">
                                        @if($i == 1)
                                        <label for="part-number">Part Number</label>
                                        @endif
                                        <input type="text" class="form-control" name="part_number[]" required value="{{ $partRequest->part_number }}" id="part-number">
                                    </div>
                                    <div class="col-md-4" style="margin-bottom: 20px;">
                                        @if($i == 1)
                                        <label for="part-number">Quantity</label>
                                        @endif
                                        <input type="number" name="qty[]" value="{{ $partRequest->qty }}" required class="form-control">
                                    </div>
                                    <div class="col-md-4 part-request-last-field"
                                         style="margin-bottom: 20px; position: relative;">
                                        @if($i == 1)
                                        <label for="part-number">Cost</label>
                                        @endif
                                        <div class="input-group ig-full">
                                <span class="input-group-addon" style="border-right: 1px solid #e5e6e7;
    position: absolute;
    left: 0;    z-index: 111111;">$</span>
                                            <input type="number" step="1" placeholder="0.00" required value="{{ CurrencyHelpers::formatPrice($partRequest->cost,'5',false) }}"
                                                   style="padding-left: 35px;" name="cost[]" class="form-control">
                                        </div>
                                            @if($i > 1)
                                        <i class="fa fa-times tips remove-part-request-fields" title="" onclick="removePartRequest('part-request-field_{{ $i }}');" style="position: absolute; cursor: pointer; top: 7px; font-size: 18px; color: #e00f0f; right:0px;" data-original-title="Remove"></i>
                                       @endif
                                        </div>
                                           <div style="clear: both;"></div>

                                        </span>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                @else
                                                    <span class="part-request-field" id="part-request-field_1">
                                    <div class="col-md-4" style="margin-bottom: 20px;">
                                        <label for="part-number">Part Number</label>
                                        <input type="text" required class="form-control" name="part_number[]" id="part-number">
                                    </div>
                                    <div class="col-md-4" style="margin-bottom: 20px;">
                                        <label for="part-number">Quantity</label>
                                        <input type="number" required name="qty[]" class="form-control">
                                    </div>
                                    <div class="col-md-4 part-request-last-field"
                                         style="margin-bottom: 20px; position: relative;">
                                        <label for="part-number">Cost</label>
                                        <div class="input-group ig-full">
                                <span class="input-group-addon" style="border-right: 1px solid #e5e6e7;
    position: absolute;
    left: 0;    z-index: 111111;">$</span>
                                            <input type="number" step="1" placeholder="0.00" value=""
                                                   style="padding-left: 35px;" required name="cost[]" class="form-control">
                                        </div>
                                        </div>
<div style="clear: both;"></div>
                                        </span>
                                                @endif
                                    </span>



                                    <div class="col-md-12">
                                        <input type="button" class="btn btn-primary pull-right addmorepartfields" id="addmorepartfields" value="Add More">
                                    </div>
                                </div>

                            </div>
                            </div>
                        </div>
                </div>

                <div class="row">
                    <div style="display: none; visibility: hidden;" class="col-md-6">
                <div class="form-group  " >
                    <label for="Status" class=" control-label col-md-3 text-left">
                        {!! SiteHelpers::activeLang('Status', (isset($fields['Status']['language'])? $fields['Status']['language'] : array())) !!}
                    </label>
                    <div class="col-md-9">
                        @if($isAdd || !$canChangeStatus)
                        <input type='hidden' name='oldStatus' value='{{ $status }}' />
                        <input type='hidden' name='Status' value='{{ empty($status) ? 'open' : $status }}' />
                        <input type="text" readonly class="form-control" value="{{ $ticketStatusLabel }}" />
                        @else
                        <input type='hidden' name='oldStatus' value='{{ $status }}' />
                        <select name='Status' required class='select2 '>
                            @foreach($statusOptions as $key => $val)
                            <option  value ='{{ $key }}'
                                     @if($status == $key) selected='selected' @endif
                            >{{ $val }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                </div>
        </div>

                </div>




            </fieldset>
        </div>

        @if (!$isAdd)
        {!! Form::hidden('Created', $row['Created']) !!}
        {!! Form::hidden('department_id', $row['department_id']) !!}
        {!! Form::hidden('assign_to', $row['assign_to']) !!}
        @endif

        <div class="form-group clearfix">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
            </div>
        </div>
        {!! Form::close() !!}

        @if($setting['form-method'] =='native')
    </div>
</div>
@endif

<script type="text/javascript">
    $(document).ready(function() {

        $("#location_id").jCombo("{{ URL::to('sbticket/comboselect?filter=location:id:id|location_name') }}" + "&delimiter=%20|%20",
            {  selected_value : '{{ $locationId }}','initial-text': "Select Location" ,
                <?php $locationId == '' ? '': print_r("onLoad:addInactiveItem('#location_id', ".$locationId." , 'Location', 'active' , 'id | location_name' )") ?>
            });
$(document).on('change','#location_id',function(){
   var locationId = $(this).val();

        $.ajax({
            url:'/servicerequests/location-games',
            data:{location_id:locationId},
            type:"GET",
            success:function(response){
                if(response.status == 'error'){
                    if(locationId !='') {
                        notyMessageError(response.message);
                    }
                }else{

                    $("#game_id").html(response.gameOptions).change();
                    @if(!empty($row['game_id']))
                    $("#game_id").val({{ $row['game_id'] }}).change();
                    @endif
                }
            }
        });
});
        setTimeout(function(){
            var locationId = $('#location_id').val();

            $.ajax({
                url:'/servicerequests/location-games',
                data:{location_id:locationId},
                type:"GET",
                success:function(response){
                    if(response.status == 'error'){
                        notyMessageError(response.message);
                    }else{
                        $("#game_id").html(response.gameOptions).change();
                        @if(!empty($row['game_id']))
                        $("#game_id").val({{ $row['game_id'] }}).change();
                        @endif
                    }
                }
            });

        },3000);

        $('.datepickerHandleButton').click(function(){
            $("#my-datepicker").datepicker().focus();
        });
        $('select[name=Priority]').change(function(){
            var elm = $(this),
                val = elm.val(),
                isSameDay = val == 'urgent',
                date = elm.data('current-date'),
                formattedDate,
                datePicker = $("#my-datepicker"),
                datePickerVal = datePicker.val();

            if (isSameDay && !datePickerVal) {
                formattedDate = $.datepicker.formatDate('mm/dd/yy', new Date(date));
                datePicker.datepicker('update', formattedDate);
            }
        });

        $('.editor').summernote();

        $('.previewImage').fancybox();

        $('.tips').tooltip();

        renderDropdown($(".select2"), { width:"100%"});
        console.log( new Date() );
        $('.date').datepicker({format:'mm/dd/yyyy',startDate:new Date(Date.now() - 864e5),autoclose:true});

        $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});

        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });

        $('.removeCurrentFiles').on('click',function(){
            var removeUrl = $(this).attr('href');
            $.get(removeUrl,function(response){});
            $(this).parent('div').empty();
            return false;
        });

        var form = $('#sbticketFormAjax');
        form.parsley();
        form.submit(function(){
            if(form.parsley('isValid') == true ){
                var options = {
                    dataType:      'json',
                    beforeSubmit :  showRequest,
                    success:       showResponse
                }
                $(this).ajaxSubmit(options);
                return false;

            } else {
                return false;
            }

        });
    });

    function showRequest()
    {
        $('.ajaxLoading').show();
    }
    function showResponse(data)  {

        if(data.status == 'success')
        {
            ajaxViewClose('#' + pageModule);
            //ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
            //window.location.href=window.location;
            $(".reloadDataButton").click();
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }
    }
$(function () {
    setTimeout(function(){
        var issueTypeValue =  $('#issue_type_id').val();
        if(Number(issueTypeValue) == 2){
            $('#part-requests-contianer input[type="text"], #part-requests-contianer input[type="number"]').attr('required','required');
        }else{
            $('#part-requests-contianer input[type="text"], #part-requests-contianer input[type="number"]').removeAttr('required');
        }
        $('#sbticketFormAjax').parsley().destroy();
        $('#sbticketFormAjax').parsley();
    },1000);
});
</script>

<style>
    .file_pathUpl input, .attachmentInputs input {
        margin-bottom: 14px;
        width: 100%;
        margin-top: 0px;
    }
</style>
