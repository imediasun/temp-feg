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
            {!! Form::open(array('url'=>'spareparts/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'sparepartsFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> Spare Parts</legend>
                    @if($row['status_id']==0)
                    <div class="form-group  ">
                        <label for="Description" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Description', (isset($fields['description']['language'])?
                            $fields['description']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('description', $row['description'],array('class'=>'form-control',
                            'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="For Game" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('For Game', (isset($fields['for_game']['language'])?
                            $fields['for_game']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('for_game', $row['for_game'],array('class'=>'form-control',
                            'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="Qty" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Qty', (isset($fields['qty']['language'])?
                            $fields['qty']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('qty', $row['qty'],array('class'=>'form-control', 'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Approx. Value" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Value', (isset($fields['value']['language'])?
                            $fields['value']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('value', $row['value'],array('class'=>'form-control', 'placeholder'=>'', ))
                            !!}
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    @endif
                    <div class="form-group  ">
                        <label for="Loc Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Location', (isset($fields['loc_id']['language'])?
                            $fields['loc_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                           <select name="loc_id" id="loc_id" class="select4"></select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    @if($row['status_id']==0)
                    <div class="form-group  ">
                        <label for="User" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Submitted By', (isset($fields['user']['language'])?
                            $fields['user']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('user', $row['user'],array('class'=>'form-control', 'placeholder'=>'', )) !!}
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    @endif
                    <div class="form-group  ">
                        <label for="Status Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Status', (isset($fields['status_id']['language'])?
                            $fields['status_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name="status_id" id="status_id" class="select4"/>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    @if($row['status_id']==0)
                    <div class="form-group  ">
                        <label for="User Claim" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('User Claim', (isset($fields['user_claim']['language'])?
                            $fields['user_claim']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            {!! Form::text('user_claim', $row['user_claim'],array('class'=>'form-control',
                            'placeholder'=>'', )) !!}
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
            $("#status_id").jCombo("{{ URL::to('spareparts/comboselect?filter=spare_status:id:status') }}",
                    {selected_value: '{{ $row['status_id'] }}', initial_text: 'Select Status'});
            $("#loc_id").jCombo("{{ URL::to('spareparts/comboselect?filter=location:id:location_name') }}",
                    {selected_value: '{{ $row['loc_id'] }}', initial_text: 'Select Location'});

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
            var form = $('#sparepartsFormAjax');
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