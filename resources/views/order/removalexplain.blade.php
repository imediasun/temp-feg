@extends('layouts.app')

@section('content')
    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Request Removal <small>Order Removal Request </small></h3>

            </div>
        </div>

        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> Request Removal <small>Order Removal Request</small></h4></div>
                <div class="sbox-content">
                    <div class="row" >
                        <div class="col-md-8" style="">
                            <h1>Request Removal Explain</h1>
                        </div>

                        <div class="col-md-6" style="margin-left:16px;">
                            {!! Form::open(array('url'=>'order/removalrequest', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'removalrequestform')) !!}
                            <div class="form-group">
                                <textarea rows="8" cols="80" 
                                          name="explaination" id="explaination"
                                          placeholder="Please Explain Why?"
                                          required></textarea>
                                <input type="hidden" value="{{$po_number }}" name="po_number"/>
                            </div>
                            <div class="form-group" style="text-align: center;">
                                <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa  fa-save "></i>  Send Request </button>
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

    </script>



@stop