
@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'vendor/vendor-import-schedule/'.$vendorId, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'importVendorScheduleFormAjax')) !!}
            <div class="col-md-12">
                <fieldset><legend> Schedule Report</legend>

                    {{--<div class="form-group hidethis " style="display:none;">--}}
                        {{--<label for="Id" class=" control-label col-md-4 text-left">--}}
                            {{--{!! SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) !!}--}}
                        {{--</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}

                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group  " >
                        <label for="Repeats" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Repeats', (isset($fields['reoccur_by']['language'])? $fields['reoccur_by']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {{--{!! Form::text('reoccur_by', $row['reoccur_by'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}--}}
                            {!! Form::select(
                                        'reoccur_by',
                                        ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'],
                                        $schedule->reoccur_by,
                                        ['class'=>'form-control', 'required'=>'true', 'id'=>'repeatType']
                                      )
                            !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  " id="repeatsOn" @if($schedule->reoccur_by == 'weekly') style="display: block" @else style="display: none" @endif>
                        <label for="Repeats On" class=" control-label col-md-4 text-left">
                            Repeats On
                        </label>

                        <div class="col-md-6">
                            {!! Form::checkbox('days[]', 'sunday', in_array("sunday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('sunday', 'S') !!}

                            {!! Form::checkbox('days[]', 'monday', in_array("monday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('monday', 'M') !!}

                            {!! Form::checkbox('days[]', 'tuesday', in_array("tuesday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('tuesday', 'T') !!}

                            {!! Form::checkbox('days[]', 'wednesday', in_array("wednesday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('wednesday', 'W') !!}

                            {!! Form::checkbox('days[]', 'thursday', in_array("thursday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('thursday', 'T') !!}

                            {!! Form::checkbox('days[]', 'friday', in_array("friday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('friday', 'F') !!}

                            {!! Form::checkbox('days[]', 'saturday', in_array("saturday", explode(',',$schedule->days)) ? true : false) !!}
                            {!! Form::label('saturday', 'S') !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  " id="repeatsOnDate" @if($schedule->reoccur_by == 'monthly') style="display: block" @else style="display: none" @endif>
                        <label for="Repeats On" class=" control-label col-md-4 text-left">
                            Repeats On
                        </label>
                        <div class="col-md-6">
                            {!! Form::selectRange('date', 01, 31, $schedule->date,array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  " id="repeatsOnDateMonth" @if($schedule->reoccur_by == 'yearly') style="display: block" @else style="display: none" @endif>
                        <label for="Repeats On" class=" control-label col-md-4 text-left">
                            Repeats On
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('date_month',$schedule->date.'/'.$schedule->month,array('class'=>'form-control date-month', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  " >
                        <label for="Summery" class=" control-label col-md-4 text-left">
                            Summery
                        </label>
                        <div class="col-md-6" id="summeryText" style="text-transform:capitalize">
                            {!! $schedule->reoccur_by  !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                </fieldset>
            </div>

            <div style="clear:both"></div>

            <div class="form-group">
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


    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#repeatType').on('change', function() {

                var repeatValue = $(this).val();
                $('#summeryText').text(repeatValue);

                if(repeatValue == 'daily'){
                    $('#repeatsOn').hide();
                    $('#repeatsOnDate').hide();
                    $('#repeatsOnDateMonth').hide();
                }
                else if(repeatValue == 'weekly'){
                    $('#repeatsOnDate').hide();
                    $('#repeatsOnDateMonth').hide();
                    $('#repeatsOn').show();
                }
                else if(repeatValue == 'monthly'){
                    $('#repeatsOn').hide();
                    $('#repeatsOnDateMonth').hide();
                    $('#repeatsOnDate').show();
                }else{
                    $('#repeatsOn').hide();
                    $('#repeatsOnDate').hide();
                    $('#repeatsOnDateMonth').show();
                }
            });
            $('.date-month').datepicker({
                format:'dd/mm',
                weekStart: 1,
                startView: 1,
                maxViewMode: 1,
                autoclose:true
            });

            var form = $('#importVendorScheduleFormAjax');
            form.parsley();
            form.submit(function(){

                if(form.parsley('isValid') == true){
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
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }

    </script>