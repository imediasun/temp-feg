<div style="width:70%;margin:0px auto">

    {!! Form::open(array('url'=>'tablecols/config/', 'class'=>'form-horizontal','files' => true ,
    'parsley-validate'=>'','novalidate'=>' ','id'=> 'tablecolsFormAjax')) !!}
    <input type="hidden" name="module_id" value="{{ $module_id }}"/>
    <input type="hidden" name="user_id" value="{{ $user_id }}"/>

    <div class="form-group form-group-sm col-md-12">
        <input type="text" name="config_name" id="configname" class="form-control" required
               placeholder="Enter Configuration Name:"/>
    </div>
    <div class="form-group">
    <label for="pre-selected-options" class="label-control">Columns</label><br/>
    <select name="cols[]" id='pre-selected-options' multiple='multiple'>
        @foreach($allColumns as $columns)
            <option value="{{ $columns['field'] }}"> {{ $columns['label'] }} </option>
        @endforeach
    </select>
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
<script>$('#pre-selected-options').multiSelect();
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
                window.location.reload();
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }


    });
</script>
