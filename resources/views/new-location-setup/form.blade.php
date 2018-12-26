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
                            {!! SiteHelpers::activeLang('Location', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name="location_id" id="location_id" required class="select2 location_id"></select>
                            <div class="clear"></div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Debit Type" class=" control-label col-md-4 text-left">
                            Debit Type
                        </label>
                        <div class="col-md-6">
                            <b>
                                <div id="debit_type"
                                     style="background: rgb(244, 244, 244); width: 100%; min-height: 30px; padding: 5px; cursor: not-allowed;">
                                    -
                                </div>
                            </b>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Teamviewer ID" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Teamviewer ID', (isset($fields['teamviewer_id']['language'])? $fields['teamviewer_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('teamviewer_id', $row['teamviewer_id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Teamviewer Passowrd" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Teamviewer Passowrd', (isset($fields['teamviewer_passowrd']['language'])? $fields['teamviewer_passowrd']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('teamviewer_passowrd', $row['teamviewer_passowrd'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Should server be locked?" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Should server be locked?', (isset($fields['is_server_locked']['language'])? $fields['is_server_locked']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="checkbox" name="is_server_locked" data-size="mini" data-name="is_server_locked"
                                   checked data-handle-width="40px" data-on-text="YES" data-off-text="NO"
                                   id="toggle_trigger_1" onswitchchange="trigger()">
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Windows User" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Windows User', (isset($fields['windows_user']['language'])? $fields['windows_user']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('windows_user', $row['windows_user'],array('class'=>'form-control', 'placeholder'=>'',    )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Windows User Password" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Windows User Password', (isset($fields['windows_user_password']['language'])? $fields['windows_user_password']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('windows_user_password', $row['windows_user_password'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Remote Desktop Needed?" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Remote Desktop Needed?', (isset($fields['is_remote_desktop']['language'])? $fields['is_remote_desktop']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">

                            <input type="checkbox" name="is_remote_desktop" data-name="is_remote_desktop"
                                   data-size="mini" data-handle-width="40px" data-on-text="YES" data-off-text="NO"
                                   id="toggle_trigger_2" onswitchchange="trigger()">
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="RDP Computer Name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('RDP Computer Name', (isset($fields['rdp_computer_name']['language'])? $fields['rdp_computer_name']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('rdp_computer_name', $row['rdp_computer_name'],array('class'=>'form-control', 'placeholder'=>'', 'disabled' => 'disabled'   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="RDP Computer User" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('RDP Computer User', (isset($fields['rdp_computer_user']['language'])? $fields['rdp_computer_user']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('rdp_computer_user', $row['rdp_computer_user'],array('class'=>'form-control', 'placeholder'=>'', 'disabled' => 'disabled'  )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="RDP Computer Passowrd" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('RDP Computer Passowrd', (isset($fields['rdp_computer_password']['language'])? $fields['rdp_computer_password']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('rdp_computer_password', $row['rdp_computer_password'],array('class'=>'form-control', 'placeholder'=>'', 'disabled' => 'disabled'  )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

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

    <script type="text/javascript">
        $(document).ready(function () {
            $("[id='toggle_trigger_1']").bootstrapSwitch({onColor: 'primary', offColor: 'default'});
            $("[id='toggle_trigger_2']").bootstrapSwitch({onColor: 'primary', offColor: 'default'});

            $("[id='toggle_trigger_1']").on('switchChange.bootstrapSwitch', function (event, state) {

                if (state == false) {
                    $('input[name="windows_user"]').prop('disabled', true);
                    $('input[name="windows_user_password"]').prop('disabled', true);
                } else {
                    $('input[name="windows_user"]').prop('disabled', false);
                    $('input[name="windows_user_password"]').prop('disabled', false);
                }
            });
            $("[id='toggle_trigger_2']").on('switchChange.bootstrapSwitch', function (event, state) {

                if (state == false) {
                    $('input[name="rdp_computer_name"]').prop('disabled', true);
                    $('input[name="rdp_computer_user"]').prop('disabled', true);
                    $('input[name="rdp_computer_password"]').prop('disabled', true);
                } else {
                    $('input[name="rdp_computer_name"]').prop('disabled', false);
                    $('input[name="rdp_computer_user"]').prop('disabled', false);
                    $('input[name="rdp_computer_password"]').prop('disabled', false);
                }
            });
            $("#location_id").jCombo(
                    "{{ URL::to('location/comboselect?filter=location:id:id|location_name ') }}",
                    {
                        excludeItems: {{ json_encode($excludedUserLocations) }},
                        selected_value: "{{ $row["location_id"]}}",
                        initial_text: '-------- Select Location --------'
                    });
            $(document).on('change', "#location_id", function () {
                var location_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ $pageModule }}/location-info",
                    data: {location_id: location_id},
                    success: function (response) {
                        $('#debit_type').text(response.debit_type);
                    }
                });
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

    </script>