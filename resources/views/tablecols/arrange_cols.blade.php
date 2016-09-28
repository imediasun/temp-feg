<div class="col-md-10">
    {!! Form::open(array('url'=>'tablecols/config/', 'class'=>'form-horizontal','files' => true ,
    'parsley-validate'=>'','novalidate'=>' ','id'=> 'tablecolsFormAjax')) !!}
    <input type="hidden" name="module_id" value="{{ $module_id }}"/>
    <input type="hidden" name="user_id" value="{{ $user_id }}"/>

    <div class="form-group form-group-sm col-md-12">
        <input type="text" name="config_name" id="configname" class="form-control" required
               placeholder="Enter Configuration Name:"/>
    </div>
    <div class="form-group col-md-12">
    <label for="pre-selected-options" class="label-control">Columns</label><br/>
    <select name="cols[]"  id='keep-order' multiple='multiple'>
        @foreach($allColumns as $columns)
            @if($pageModule == 'throwreport')
                @if($columns['view'] == 1 )
                    <option value="{{ $columns['field'] }}"> {{ $columns['label'] }} </option>
                @endif
            @else
                <option value="{{ $columns['field'] }}"> {{ $columns['label'] }} </option>
            @endif
        @endforeach
    </select>
        <input type="hidden" name="multiple_value" id="multiple_value"  />
        </div>
    <div id="groups" class="form-group form-group-sm  col-md-12" >
    <label for="pre-selected-options1" class="label-control">Groups</label><br/>
    <select name="group_id" class="form-control">
        <option value="0">Select Group</option>
        @foreach($groups as $group)
            <option value="{{ $group->group_id }}"> {{ $group->name }} </option>
        @endforeach
    </select>
    </div><div class="clearfix"></div>
    <div style="text-align:center">
    <div class="radio-inline">
        <label for="public"><input type="radio" name="user_mode" value="0" checked id="public"/> Public </label>
    </div>
    <div class="radio-inline">
        <label for="private"><input type="radio" name="user_mode" _mode value="1" id="private"/> Private </label>
    </div>
    <div class="clearfix"></div>
    <br/>
    <button type="submit" class="btn btn-primary" style="width:130px;height:35px" value="Submit" name="submit"
            id="submit">Submit
    </button>
    </div>
    {!! Form::close() !!}
</div>
{{--<div class="col-md-2" style="margin-top:130px">
<button class="btn btn-small btn-primary tips" title ="Move Up" id="upbtn"><span class="fa fa-arrow-up" ></span></button>
<button class="btn btn-small btn-primary"  title ="Move Down" id="downbtn"><span class="fa fa-arrow-down"></span></button>
</div>--}}
<div class="clearfix"></div>

<script>
    $('#keep-order').multiSelect({
        keepOrder: true,
        afterSelect: function(value, text){
            var get_val = $("#multiple_value").val();
            var hidden_val = (get_val != "") ? get_val+"," : get_val;
            $("#multiple_value").val(hidden_val+""+value);
        },
        afterDeselect: function(value, text){
            var get_val = $("#multiple_value").val();
            var new_val = get_val.replace(value, "");
            $("#multiple_value").val(new_val);
        }
    });
    $("#upbtn").on('click',function(){
    //alert('Up Button is Pressed');

    });

    $("#downbtn").on('click',function(){
       // alert('Down Button is Pressed');

    });
    $("#public,#private").change(function () {
        if ($("#public").is(":checked")) {
            $('#groups').show();
        }
        else {
            $('#groups').hide();
        }
    });

</script>

<script>
    $(document).ready(function () {

        $(".ms-container").css('width','100%');
         var form = $('#tablecolsFormAjax');
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
        function showRequest() {
            $('.ajaxLoading').show();
        }

        function showResponse(data) {
            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}}');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
                $('.ajaxLoading').hide();
                //window.location.reload();
                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + data.id);
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }


    });
</script>
<script>
    $(function(){
        $('#countries').multiSelect({
            afterSelect: function(value, text){
                var get_val = $("#multiple_value").val();
                var hidden_val = (get_val != "") ? get_val+"," : get_val;
                $("#multiple_value").val(hidden_val+""+value);
            },
            afterDeselect: function(value, text){
                var get_val = $("#multiple_value").val();
                var new_val = get_val.replace(value, "");
                $("#multiple_value").val(new_val);
            }
        });
    });
</script>
