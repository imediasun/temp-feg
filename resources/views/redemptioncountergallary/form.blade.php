@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="background-color:#FFF;box-shadow:1px 1px 5px lightgray;padding:40px">
                    <h1>Add to Redemption Gallery</h1>
                    <hr/>
                    {!! Form::open(array('url'=>'redemptioncountergallary/save?return='.$return, 'class'=>'form-horizontal','files'
                    => true , 'parsley-validate'=>'','novalidate'=>' ','id' => 'gallaryfileuploadform')) !!}

                    <div class="form-group">
                        <label for="gallary_img" class="control-label col-md-3">Add Photo</label>
                        <div class="col-md-9">
                            <input type='file' name='redemption_image[]' id='redemption_img' required multiple value=""/>
                        </div>
                        </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6">
                            <p class="bg-info" style="padding: 5px">You may upload multiple images by pressing and holding down the CTRL button on your keyboard while you are selecting images to upload.<br>File type can be <b>Jpeg, Png or Gif</b></p>

                        </div>
                    </div>
                        <div class="form-group">
                            <label for="location_id" class="control-label col-md-3">Location</label>

                            <div class="col-md-6">
                                <select name="location" id="location_id" class="select3" required></select>
                            </div>
                        </div>
                        <div style="text-align:center">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm"><i
                                        class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
                            <button type="button" onclick="location.href='{{ URL::to('redemptioncountergallary') }}' "
                                    class="btn btn-success btn-sm "><i
                                        class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }}
                            </button>
                        </div>

                    {!! Form::close() !!}
                    </div><div class="clearfix"></div>
                    <ul class="parsley-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @if($setting['form-method'] =='native')
            </div>
        </div>
        @endif
        <script type="text/javascript">
            $(document).ready(function () {

                $("#location_id").jCombo("{{ URL::to('redemptioncountergallary/comboselect?filter=location:id:id|location_name') }}",
                        {  selected_value : '',initial_text: 'Select Location' });

                $('.editor').summernote();

                renderDropdown($(".select2,.select3"), { width:"100%"});

                var form = $('#gallaryfileuploadform');
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
                form.parsley();


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
