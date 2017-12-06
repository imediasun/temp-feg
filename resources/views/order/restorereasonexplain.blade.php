@extends('layouts.app')

@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Reason for Restore Order </h3>
            </div>
        </div>

        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> Reason for Restore Order </h4></div>
                <div class="sbox-content">
                    <div class="row" >
                        <div class="col-md-8" style="">
                            <h1>Reason for Restore Order</h1>
                        </div>

                        <div class="col-md-7" style="margin-left:16px;">
                            {!! Form::open(array('url'=>'order/restoreorder', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'removalreasonform','onsubmit'=>'return validateReason()')) !!}
                            <div id="txtarea" class="form-group">
                                <textarea onkeyup="removeErrorMessage(this);"  minlength="10" min="10" rows="9"
                                          name="explaination" id="explaination"
                                          placeholder="Please Explain Why?"
                                          style="width: 100%;"
                                          required></textarea>
                                <input type="hidden" value="{{$ids }}" name="ids"/>
                            </div>
                            <div class="form-group" style="text-align: center;">
                                <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa  fa-save "></i>  Submit </button>
                                <button type="button" onclick="location.href='{{ URL::to('order?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>

                            </div>
                            {!! Form::close() !!}
                        </div>
                        <ul class="parsley-error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        </div>
    <script>

        $(document).ready(function()
        {
        var form = $('#removalrequestform');
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
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
                location.href='{{ url() }}'+'/order';
                
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        function removeErrorMessage(obj){
            if((obj.value).length >0) {
                $("#parsley-15829651775452813").remove();
            }
        }
        function validateReason(){

            var obj = $("#explaination");
            if((obj.val()).length <10){
                var html = '<ul id="parsley-15829651775452813" class="parsley-error-list"><li class="required" style="display: list-item;">At least 10 characters must be entered.</li></ul>';
                $("#txtarea").append(html);
                return false;
            }
            return true;
        }

    </script>



@stop