

@if($setting['form-method'] =='native')

    <div class="sbox" xmlns="http://www.w3.org/1999/html">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'new-location-setup/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'newlocationsetupFormAjax')) !!}

            <div class="col-md-12">
                <fieldset>
                    <legend> New Location Setup</legend>

                    <div class="form-group  ">
                        <label for="Location" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Location Name', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name="location_id" id="location_id"  required class="select2 location_id"></select>
                            <div class="clear"></div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    {{--<div class="form-group  ">--}}
                        {{--<label for="Location Type" class=" control-label col-md-4 text-left">--}}
                            {{--Debit Type--}}
                        {{--</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<b>--}}
                                {{--<div id="debit_type"--}}
                                     {{--style="background: rgb(244, 244, 244); width: 100%; min-height: 30px; padding: 5px; cursor: not-allowed;">--}}
                                    {{-----}}
                                {{--</div>--}}
                            {{--</b>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}

                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group  ">
                        <label for="Location ID" class=" control-label col-md-4 text-left">
                            Location ID
                        </label>
                        <div class="col-md-6">
                            <b>
                                <div id="debit_type"></div>
                            </b>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<label for="Should server be locked?" class=" control-label col-md-4 text-left">--}}
                            {{--{!! SiteHelpers::activeLang('Use Tv', (isset($fields['use_tv']['language'])? $fields['use_tv']['language'] : array())) !!}--}}
                        {{--</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<input type="checkbox" {{ $row['use_tv'] == 1 ? "checked":""}}  name="use_tv" data-size="mini" data-name="use_tv"--}}
                                    {{--data-handle-width="40px" data-on-text="YES" data-off-text="NO"--}}
                                   {{--id="toggle_trigger_4" onswitchchange="trigger()">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}

                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group  ">
                        <label for="Teamviewer ID" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('TeamViewer ID', (isset($fields['teamviewer_id']['language'])? $fields['teamviewer_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <?php
                            $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
//                                if($row['use_tv'] == 0 || $row['use_tv'] == ''){
//                                    $parems['disabled']='disabled';
//                                }
                            ?>
                            {!! Form::text('teamviewer_id', $row['teamviewer_id'],$parems) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Teamviewer Passowrd" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('TeamViewer Password', (isset($fields['teamviewer_passowrd']['language'])? $fields['teamviewer_passowrd']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <?php
                            $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
//                            FEG_ID
                            ?>
                            {!! Form::text('teamviewer_passowrd', $row['teamviewer_passowrd'],$parems) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Should server be locked?" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Lock Server?', (isset($fields['is_server_locked']['language'])? $fields['is_server_locked']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="checkbox"  {{$row['is_server_locked']==1? "checked":""}}  name="is_server_locked" data-size="mini" data-name="is_server_locked"
                                    data-handle-width="40px" data-on-text="YES" data-off-text="NO"
                                   id="toggle_trigger_1" onswitchchange="trigger()">
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div id="wrapremotedesktop">
                    <div class="form-group  ">
                        <label for="Windows User" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Windows User', (isset($fields['windows_user']['language'])? $fields['windows_user']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <?php
                            $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
//                            if($row['is_server_locked'] == 0 || $row['is_server_locked'] == ''){
//                                $parems['disabled']='disabled';
//                            }
                            ?>
                            {!! Form::text('windows_user', $row['windows_user'],$parems )!!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Windows User Password" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Windows Password', (isset($fields['windows_user_password']['language'])? $fields['windows_user_password']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <?php
                            $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
//                            if($row['is_server_locked'] == 0 || $row['is_server_locked'] == ''){
//                                $parems['disabled']='disabled';
//                            }
                            ?>
                            {!! Form::text('windows_user_password', $row['windows_user_password'],$parems) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="Remote Desktop Needed?" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Remote Desktop Needed?', (isset($fields['is_remote_desktop']['language'])? $fields['is_remote_desktop']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="checkbox"  {{$row['is_remote_desktop']==1? "checked":""}}  name="is_remote_desktop" data-name="is_remote_desktop"
                                   data-size="mini" data-handle-width="40px" data-on-text="YES" data-off-text="NO"
                                   id="toggle_trigger_2" onswitchchange="trigger()">
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div id="wraprdpfields">
                    <div class="form-group ">
                        <label for="RDP Computer Name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Computer Name', (isset($fields['rdp_computer_name']['language'])? $fields['rdp_computer_name']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <?php
//                                $parems = array('class'=>'form-control', 'placeholder'=>'', 'required' =>''   );
//                            if(!in_array(strtolower($row['is_remote_desktop']),  ['yes',1])){
//                                $parems['disabled'] = 'disabled';
//                            }
                            ?>
                            {!! Form::text('rdp_computer_name', $row['rdp_computer_name'],$parems) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="RDP Computer User" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('User', (isset($fields['rdp_computer_user']['language'])? $fields['rdp_computer_user']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
<?php
//                            $parems = array('class'=>'form-control', 'placeholder'=>'', 'required' =>''   );
//                            if(!in_array(strtolower($row['is_remote_desktop']),  ['yes',1])){
//                                $parems['disabled'] = 'disabled';
//                            }
                            ?>
                            {!! Form::text('rdp_computer_user', $row['rdp_computer_user'],$parems) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="RDP Computer Passowrd" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Password', (isset($fields['rdp_computer_password']['language'])? $fields['rdp_computer_password']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <?php
//                            $parems = array('class'=>'form-control', 'placeholder'=>'', 'required' =>''   );
//                            if(!in_array(strtolower($row['is_remote_desktop']),  ['yes',1])){
//                                $parems['disabled'] = 'disabled';
//                            }
                            ?>
                            {!! Form::text('rdp_computer_password', $row['rdp_computer_password'],$parems) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    </div>
                   @if($editState==1)
                        <div class="form-group  ">
                            <label for="Windows User" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('VM URL', (isset($fields['vm_url']['language'])? $fields['vm_url']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php
                                $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
                                ?>
                                {!! Form::text('vm_url', $row['vm_url'],$parems )!!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Windows User" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('VM User', (isset($fields['vm_user']['language'])? $fields['vm_user']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php
                                $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
                                ?>
                                {!! Form::text('vm_user', $row['vm_user'],$parems )!!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Windows User" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('VM Password', (isset($fields['vm_password']['language'])? $fields['vm_password']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php
                                $parems = array('class'=>'form-control', 'placeholder'=>'','required' =>''   );
                                ?>
                                {!! Form::text('vm_password', $row['vm_password'],$parems )!!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Sync Install" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Sync Install', (isset($fields['sync_install']['language'])? $fields['sync_install']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                            <input type="radio" {{ $row['sync_install'] == 1 ? "checked":""}}  name="sync_install" data-size="mini" data-name="sync_install"
                                data-handle-width="40px" value="1"> Yes
                                <input type="radio" {{ $row['sync_install'] == 0 ? "checked":""}}  name="sync_install" data-size="mini" data-name="sync_install"
                                data-handle-width="40px" value="0"> No
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="form-group  ">
                            <label for="Windows User" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Sync Time', (isset($fields['vm_password']['language'])? $fields['sync_time']['language'] : array())) !!}
                            </label>
                            <div class="col-md-4">
                                <?php
                                $parems = array('id'=>'synctime', 'class'=>'form-control', 'placeholder'=>'','required' =>''   );
                                ?>
                                {!! Form::text('sync_time', $row['sync_time'],$parems )!!}
                            </div>
                            <div class="col-md-2 form-group">
                                <select name="sync_time_zone" id="time-zons" class="form-control">
                                    <option value="PST">PST</option>
                                    <option value="PDT">PDT</option>
                                    <option value="MST">MST</option>
                                    <option value="MDT">MDT</option>
                                    <option value="MDT">CST</option>
                                    <option value="MDT">CDT</option>
                                    <option value="MDT">EST</option>
                                    <option value="MDT">EST</option>
                                </select>
                            </div>
                            <script>
                                var timeZones =  document.getElementById('time-zons');
                               timeZones.value = '{{ $row['sync_time_zone'] }}';
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="Windows User" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Sync Difference', (isset($fields['sync_difference']['language'])? $fields['sync_difference']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php
                                $parems = array('id'=>'syncdiff','class'=>'form-control timepicker', 'placeholder'=>'','required' =>''   );
                                ?>
                                {!! Form::text('sync_difference', $row['sync_difference'],$parems )!!}
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                    @endif
                </fieldset>
            </div>

            <div style="clear:both"></div>

            <div class="form-group">
                <label class="col-sm-4 text-right">&nbsp;</label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-sm "><i
                                class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


            @if($setting['form-method'] =='native')
        </div>
    </div>
    @endif


    </div>
    <script src="{{asset('sximo/js/jquery.timepicker.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#syncdiff').timepicker({ 'timeFormat': 'H:i:s' });
            $('#synctime').timepicker({ 'timeFormat': 'H:i:s' });

            @if($row['is_remote_desktop']==1)
            $('#wraprdpfields').css("display", "block");
            @else
            $('#wraprdpfields').css("display", "none");
            $('input[name="rdp_computer_name"]').removeAttr('required');
            $('input[name="rdp_computer_user"]').removeAttr('required');
            $('input[name="rdp_computer_password"]').removeAttr('required');
            @endif
            @if($row['is_server_locked']==1)
            $('#wrapremotedesktop').css("display", "block");
            @else
            $('#wrapremotedesktop').css("display", "none");
            $('input[name="windows_user"]').removeAttr('required');
            $('input[name="windows_user_password"]').removeAttr('required');
            @endif

            $("[id='toggle_trigger_1']").bootstrapSwitch({onColor: 'primary', offColor: 'default'});
            $("[id='toggle_trigger_2']").bootstrapSwitch({onColor: 'primary', offColor: 'default'});
            $("[id='toggle_trigger_3']").bootstrapSwitch({onColor: 'primary', offColor: 'default'});
            //$("[id='toggle_trigger_4']").bootstrapSwitch({onColor: 'primary', offColor: 'default'});


            $("[id='toggle_trigger_1']").on('switchChange.bootstrapSwitch', function (event, state) {


                var windows_user = $('input[name="windows_user"]');
                var windows_user_password = $('input[name="windows_user_password"]');
                if (state == false) {
                    $('#wrapremotedesktop').css("display", "none");
//                    windows_user.prop('readonly', true);
//                    windows_user_password.prop('readonly', true);
//                    windows_user.prop('disabled', true);
//                    windows_user_password.prop('disabled', true);
                    windows_user.removeAttr('required');
                    windows_user_password.removeAttr('required');
                } else {
                    $('#wrapremotedesktop').css("display", "block");
//                    windows_user.prop('readonly', false);
//                    windows_user_password.prop('readonly', false);
//                    windows_user.prop('disabled', false);
//                    windows_user_password.prop('disabled', false);
                    windows_user.attr('required','required');
                    windows_user_password.attr('required','required');
                }
                reInitParsley();
            });

            $("[id='toggle_trigger_2']").on('switchChange.bootstrapSwitch', function (event, state) {
               var rdp_computer_name =  $('input[name="rdp_computer_name"]');
               var rdp_computer_user =  $('input[name="rdp_computer_user"]');
               var rdp_computer_password = $('input[name="rdp_computer_password"]');
                if (state == false) {
                    $('#wraprdpfields').css("display", "none");
//                    rdp_computer_name.prop('readonly', true);
//                    rdp_computer_user.prop('readonly', true);
//                    rdp_computer_password.prop('readonly', true);
//                    rdp_computer_name.prop('disabled', true);
//                    rdp_computer_user.prop('disabled', true);
//                    rdp_computer_password.prop('disabled', true);
                    rdp_computer_name.removeAttr('required');
                    rdp_computer_user.removeAttr('required');
                    rdp_computer_password.removeAttr('required');
                } else {
                    $('#wraprdpfields').css("display", "block");
//                    rdp_computer_name.prop('readonly', false);
//                    rdp_computer_password.prop('readonly', false);
//                    rdp_computer_user.prop('readonly', false);
//                    rdp_computer_name.prop('disabled', false);
//                    rdp_computer_user.prop('disabled', false);
//                    rdp_computer_password.prop('disabled', false);
                    rdp_computer_name.attr('required','required');
                    rdp_computer_user.attr('required','required');
                    rdp_computer_password.attr('required','required');
                }
                reInitParsley();
            });


            $("[id='toggle_trigger_3']").on('switchChange.bootstrapSwitch', function (event, state) {
                if (state == false) {
                    $('input[name="location_status"]').prop('disabled', true);
                } else {
                    $('input[name="location_status"]').prop('disabled', false);
                }
                reInitParsley();
            });

//            $("[id='toggle_trigger_4']").on('switchChange.bootstrapSwitch', function (event, state) {
//                var teamviewer_id = $('input[name="teamviewer_id"]');
//                var teamviewer_passowrd = $('input[name="teamviewer_passowrd"]');
//                if (state == false) {
//                    teamviewer_id.prop('readonly', true);
//                    teamviewer_passowrd.prop('readonly', true);
//                    teamviewer_id.prop('disabled', true);
//                    teamviewer_passowrd.prop('disabled', true);
//                    teamviewer_id.removeAttr('required');
//                    teamviewer_passowrd.removeAttr('required');
//                } else {
//                    teamviewer_id.prop('readonly', false);
//                    teamviewer_passowrd.prop('readonly', false);
//                    teamviewer_id.prop('disabled', false);
//                    teamviewer_passowrd.prop('disabled', false);
//
//                    teamviewer_id.attr('required','required');
//                    teamviewer_passowrd.attr('required','required');
//                }
//                reInitParsley();
//            });
            $("#location_id").jCombo(
                    "{{ URL::to('new-location-setup/comboselect?filter=location:id:location_name ') }}",
                    {
                        {{--excludeItems: {{ json_encode($excludedUserLocations) }},--}}
                        selected_value: "{{ $row["location_id"]}}",
                        initial_text: '-------- Select  Location --------'
                    });
            $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name:hide:0:status:1') }}",
                    {selected_value: '{{ $row["vendor_id"] }}' ,
                        <?php $row["vendor_id"] == '' ? '': print_r("onLoad:addInactiveItem('#vendor_id[data-seprate=true]', ".$row['vendor_id']." , 'Vendor', 'status' , 'vendor_name')") ?>
                    });

            $(document).on('change', "#location_id", function () {
                var location_id = $(this).val();
                $('#debit_type').text(location_id);
                {{--$.ajax({--}}
                    {{--type: "GET",--}}
                    {{--url: "{{ $pageModule }}/location-info",--}}
                    {{--data: {location_id: location_id},--}}
                    {{--success: function (response) {--}}
                        {{--$('#debit_type').text(response.debit_type);--}}
                    {{--}--}}
                {{--});--}}
            });
            $('.editor').summernote();
            $('.previewImage').fancybox();
            $('.tips').tooltip();
            renderDropdown($(".select2, .select3, .select4, .select5"), {width: "100%"});
            $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
            $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            $('input[type="checkbox"],input[type="radio"]').not("[id^='toggle_trigger_']").iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $('.removeCurrentFiles').on('click', function () {
                var removeUrl = $(this).attr('href');
                $.get(removeUrl, function (response) {
                });
                $(this).parent('div').empty();
                return false;
            });
            var form = $('#newlocationsetupFormAjax');
            form.parsley();
            form.submit(function () {

                if (form.parsley('isValid') == true) {
                    var options = {
                        dataType: 'json',
                        beforeSubmit: showRequest,
                        success: showResponse
                    }
                    $(this).ajaxSubmit(options);
                    return false;

                } else {
                    return false;
                }

            });

        });

        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {

            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }

        function reInitParsley(){
            var form = $('#newlocationsetupFormAjax');
            form.parsley().destroy();
            form.parsley();
        }
        
        function changeLocationId() {
            
        }

    </script>