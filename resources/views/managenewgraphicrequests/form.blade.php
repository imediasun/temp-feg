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
            {!! Form::open(array('url'=>'managenewgraphicrequests/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'managenewgraphicrequestsFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> Manage New Graphics Req</legend>
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
                        <label for="Description" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Description', (isset($fields['description']['language'])?
                            $fields['description']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
					  <textarea name='description' rows='5' id='description' class='form-control '
                              >{{ $row['description'] }}</textarea>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Media Type" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Media Type', (isset($fields['media_type']['language'])?
                            $fields['media_type']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('media_type', $row['media_type'],array('class'=>'form-control',
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
        $("#status_id").jCombo("{{ URL::to('managenewgraphicrequests/comboselect?filter=new_graphics_request_status:id:status') }}",
                {selected_value: '{{ $row['status_id'] }}', initial_text: 'Select Status'});
        $("#priority_id").jCombo("{{ URL::to('managenewgraphicrequests/comboselect?filter=new_graphics_priority:id:id_plus') }}",
                {selected_value: '{{ $row['priority_id'] }}', initial_text: 'Select Priority'});

        $('.editor').summernote();
        $('.previewImage').fancybox();
        $('.tips').tooltip();
        $(".select4").select2({width: "98%"});
        $('.date').datepicker({format: 'yyyy-mm-dd', autoClose: true})
        $('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square_green'
        });
        $('.removeCurrentFiles').on('click', function () {
            var removeUrl = $(this).attr('href');
            $.get(removeUrl, function (response) {
            });
            $(this).parent('div').empty();
            return false;
        });
        var form = $('#managenewgraphicrequestsFormAjax');
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
            ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data','view=open');
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }
    }

</script>		 