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
            {!! Form::open(array('url'=>'ordersetting/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'ordersettingFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> Order Settings</legend>

                    <div class="form-group  ">
                        <label for="Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Po Note" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Po Note', (isset($fields['po_note']['language'])? $fields['po_note']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('po_note', $row['po_note'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Is Merchandiseorder" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Is Merchandiseorder', (isset($fields['is_merchandiseorder']['language'])? $fields['is_merchandiseorder']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('is_merchandiseorder', $row['is_merchandiseorder'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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


            $('.editor').summernote();
            $('.previewImage').fancybox();
            $('.tips').tooltip();
            renderDropdown($(".select2, .select3, .select4, .select5"), {width: "100%"});
            $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
            $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            $('input[type="checkbox"],input[type="radio"]').iCheck({
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
            var form = $('#ordersettingFormAjax');
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