@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'manageservicerequests/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'manageservicerequestsFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> Manage Service Requests</legend>


                    <div class="form-group  ">
                        <label for="Priority" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Priority ', (isset($fields['priority_id']['language'])?
                            $fields['priority_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name="priority_id" id="priority_id" class="select4"></select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Date Resolved" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang(' Date Closed', (isset($fields['solved_date']['language'])?
                            $fields['solved_date']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('solved_date', $row['solved_date'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($row['solved_date'])),array('class'=>'form-control date',
                            'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="status" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('status ', (isset($fields['status_id']['language'])?
                            $fields['status_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name="status_id" id="status_id" class="select4"></select>
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
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif



<script type="text/javascript">
    $(document).ready(function () {
        $("#status_id").jCombo("{{ URL::to('manageservicerequests/comboselect?filter=service_status:id:status') }}",
                {selected_value: '{{ $row['status_id'] }}', initial_text: 'Select Status'});
        $("#priority_id").jCombo("{{ URL::to('manageservicerequests/comboselect?filter=new_graphics_priority:id:id_plus') }}",
                {selected_value: '{{ $row['priority_id'] }}', initial_text: 'Select Priority'});

        $('.editor').summernote();
        $('.previewImage').fancybox();
        $('.tips').tooltip();
        renderDropdown($(".select4 "), { width:"100%"});
        $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
        $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square_green'
        });
        $('.removeCurrentFiles').on('click', function () {
            var removeUrl = $(this).attr('href');
            $.get(removeUrl, function (response) {
            });
            $(this).parent('div').empty();
            return false;
        });
        var form = $('#manageservicerequestsFormAjax');
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