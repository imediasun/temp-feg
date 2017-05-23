
@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4>@if($id)
                    <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Merchandise Budget
                @else
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Merchandise Budget
                @endif
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'merchandisebudget/save/'.$row['id'], 'class'=>'form-horizontal','files' => true
            , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'merchandisebudgetFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <div class="form-group  ">
                        <label for="Location Id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])?
                            $fields['location_id']['language'] : array())) !!}
                        </label>

                        <div class="col-md-4">
                            <select name="location_id" id="location_id" class="select3" required/>
                        </div>
                        <div class="col-md-4">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="Budget Date" class=" control-label col-md-4 text-left">
                            Budget Year
                        </label>
                        <div class="col-md-4">
                            <?php  $years=SiteHelpers::getBudgetYears()?>
                            <select name="budget_year" class="selectpicker show-menu-arrow" data-header="Select Year" data-style="btn-default" id="budget_year">
                                @foreach($years as $year)
                                    <option @if( $year->year == $row['budget_year']) selected
                                    @endif value="{{ $year->year }}">{{ $year->year }}</option>
                                    @endforeach
                            </select>
                        </div>
                        </div>
                        <fieldset>
                            <legend> Merchandise Budget Values</legend>
                            <div class="form-group  ">
                                <label for="jan" class=" control-label col-md-4 text-left">January</label>

                                <div class="col-md-4">
                                    <input type="number" name="jan" id="jan" value="{{$row['Jan']}}"
                                           class="form-control"/>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="feb" class=" control-label col-md-4 text-left"> February</label>

                                <div class="col-md-4">
                                    <input type="number" name="feb" id="feb" value="{{ $row['Feb']}}"
                                           class="form-control"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="march" class=" control-label col-md-4 text-left">
                                    March
                                </label>

                                <div class="col-md-4">
                                    <input type="number" name="march" id="march" value="{{$row['March']}}"
                                           class="form-control"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="april" class=" control-label col-md-4 text-left">
                                    April </label>

                                <div class="col-md-4">
                                    <input type="number" name="april" id="april" class="form-control"
                                           value="{{$row['April']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="may" class=" control-label col-md-4 text-left">
                                    May </label>

                                <div class="col-md-4">
                                    <input type="number" name="may" id="may" class="form-control"
                                           value="{{$row['May']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="jun" class=" control-label col-md-4 text-left">
                                    June</label>

                                <div class="col-md-4">
                                    <input type="number" name="jun" id="jun" class="form-control"
                                           value="{{$row['June']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="jul" class=" control-label col-md-4 text-left">
                                    July </label>

                                <div class="col-md-4">
                                    <input type="number" name="jul" id="jul" class="form-control"
                                           value="{{$row['July']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="aug" class=" control-label col-md-4 text-left">
                                    August </label>

                                <div class="col-md-4">
                                    <input type="number" name="aug" id="aug" class="form-control"
                                           value="{{$row['August']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="sep" class=" control-label col-md-4 text-left">
                                    September </label>

                                <div class="col-md-4">
                                    <input type="number" name="sep" id="sep" class="form-control"
                                           value="{{$row['September']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="oct" class=" control-label col-md-4 text-left">
                                    Octuber </label>

                                <div class="col-md-4">
                                    <input type="number" name="oct" id="oct" class="form-control"
                                           value="{{$row['Octuber']}}"/>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="nov" class=" control-label col-md-4 text-left">
                                    November </label>

                                <div class="col-md-4">
                                    <input type="number" name="nov" id="nov" class="form-control"
                                           value="{{$row['November']}}"/>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="dec" class=" control-label col-md-4 text-left">
                                    December </label>

                                <div class="col-md-4">
                                    <input type="number" name="dec" id="dec" class="form-control"
                                           value="{{$row['December']}}"/></div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </fieldset>
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

        $("#location_id").jCombo("{{ URL::to('merchandisebudget/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '{{ $row['location_id'] }}' , ready:addInactiveItem("#location_id", {{ $row['location_id'] }} , 'Location', 'active' , 'location_name' , 1)});

        $('.editor').summernote();
        $('.previewImage').fancybox();
        $('.tips').tooltip();
        renderDropdown($(".select3 "), { width:"100%"});
        $('.datee').datepicker({
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years",
            changeMonth: false
        });

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
        var form = $('#merchandisebudgetFormAjax');
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
<script>
    $(function () {
        var content = "<input type=text onKeyDown='event.stopPropagation();' onKeyPress='addSelectInpKeyPress(this,event)' onClick='event.stopPropagation()' placeholder='Add Year'> <span class='glyphicon glyphicon-plus addnewicon' onClick='addSelectItem(this,event,1);'></span>";

        var divider = $('<option/>')
                .addClass('divider')
                .data('divider', true);


        var addoption = $('<option/>')
                .addClass('additem')
                .data('content', content)

        $('.selectpicker')
                .append(divider)
                .append(addoption)
                .selectpicker();

    });

    function addSelectItem(t,ev)
    {
        ev.stopPropagation();
        var txt=$(t).prev().val().replace(/[|]/g,"");
        if ($.trim(txt)=='') return;
        var p=$(t).closest('.bootstrap-select').prev();
        var o=$('option', p).eq(-2);
        o.before( $("<option>", { "selected": true, "text": txt}) );
        p.selectpicker('refresh');
    }

    function addSelectInpKeyPress(t, ev) {
        ev.stopPropagation();
        // do not allow pipe character
        if (ev.which == 124) ev.preventDefault();
        // enter character adds the option
        if (ev.which == 13) {
            ev.preventDefault();
            addSelectItem($(t).next(), ev);
        }
    }
</script>

