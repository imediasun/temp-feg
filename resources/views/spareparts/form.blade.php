@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4>@if($id)
                    <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Spare Part
                @else
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Spare Part
                @endif
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
                    

                    <div class="form-group  ">
                        <label for="Description" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Description', (isset($fields['description']['language'])?
                            $fields['description']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            @if($row['status_id']==2)
                            {!! Form::text('description', $row['description'],array('class'=>'form-control',
                            'placeholder'=>'', 'required'=>'true' )) !!}
                            @else
                                {{$row['description']}}
                            @endif
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="For Game" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('For Game', (isset($fields['game_title_id']['language'])?
                            $fields['game_title_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            @if($row['status_id']==2)
                                <select name="game_title_id" id="game_title_id" class="select4"></select>
                            @else
                                {!! SiteHelpers::gridDisplayView($row['game_title_id'],'game_title_id','1:game_title:id:game_title')!!}
                            @endif
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
                            @if($row['status_id']==2)
                                {!! Form::text('qty', $row['qty'],array('class'=>'form-control', 'placeholder'=>'','required'=>'true', 'parsley-type'=>'number' , 'parsley-min'=>1  )) !!}
                            @else
                                {{$row['qty']}}
                            @endif
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
                            @if($row['status_id']==2)
                            {!! Form::text('value', $row['value'],array('class'=>'form-control', 'placeholder'=>'','required'=>'true', 'parsley-type'=>'number' , 'parsley-min'=>0))
                            !!}
                            @else
                                {{$row['value']}}
                            @endif
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="Loc Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Location', (isset($fields['loc_id']['language'])?
                            $fields['loc_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                           <select name="loc_id" id="loc_id" class="select4" required></select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="Status Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Status', (isset($fields['status_id']['language'])?
                            $fields['status_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name="status_id" id="status_id" class="select4" required />
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  ">
                        <label for="User Claim" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('User Claim', (isset($fields['user_claim']['language'])?
                            $fields['user_claim']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            @if($row['status_id']==2)
                                {!! Form::text('user_claim', $row['user_claim'],array('class'=>'form-control',
                                'placeholder'=>'', )) !!}
                                @else
                                {{$row['user_claim']}}
                            @endif
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Claimed Location" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Claimed Location', (isset($fields['claimed_location_id']['language'])?
                            $fields['claimed_location_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name="claimed_location_id" id="claimed_location_id" class="select4" />
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    @if(!empty($row['claimed_by']))
                        <div class="form-group  ">
                            <label for="Claimed By User" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Claimed By User', (isset($fields['claimed_by']['language'])?
                                $fields['claimed_by']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! SiteHelpers::gridDisplayView($row->claimed_by,'claimed_by','1:users:id:first_name | last_name')!!}
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="claimed_by" value="{{Auth::user()->id}}">
                    @endif
                    <div class="form-group  ">
                        <label for="User" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Submitted By', (isset($fields['user']['language'])?
                            $fields['user']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            @if(empty($row['id']))
                            <input type="hidden" name="user" value="{{Auth::user()->first_name .' '. Auth::user()->last_name}}">
                            @endif
                            @if(!empty($row['user']))
                                {{$row['user']}}
                            @else
                                {{Auth::user()->first_name .' '. Auth::user()->last_name}}
                            @endif
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>



                </fieldset>
            </div>


            <div style="clear:both"></div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
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
            $("#claimed_location_id").jCombo("{{ URL::to('spareparts/comboselect?filter=location:id:location_name') }}",
                {selected_value: '{{ $row['claimed_location_id'] }}', initial_text: 'Select Location'});
            $("#loc_id").jCombo("{{ URL::to('spareparts/comboselect?filter=location:id:location_name') }}",
                    {selected_value: '{{ $row['loc_id'] }}', initial_text: 'Select Location' ,
                        <?php $row["loc_id"] == '' ? '': print_r("onLoad:addInactiveItem('#loc_id', ".$row['loc_id']." , 'Location', 'active' , 'location_name' )") ?>
                    });
            $("#game_title_id").jCombo("{{ URL::to('spareparts/comboselect?filter=game_title:id:game_title') }}" + "&delimiter= - ",
                {  selected_value : '{{ $row["game_title_id"] }}',initial_text:'Select Game' });
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
