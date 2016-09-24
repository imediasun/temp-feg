@extends('layouts.app')
@section('content')
    <div class="page-content row">
        <!-- Page header -->

            <div class="sbox-title">
                <h3 >  Save/Download PO
                   </h3>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"  id="close" onclick="reloadOrder();"
                        ><i class="fa fa fa-times"></i></a>


            </div>


        <div class="page-content-wrapper m-t">
            <div class="ajaxLoading"></div>
            <div class="sbox animated fadeInRight">
                <div class="sbox-content">
                    <div class="row" >
                        <div class="col-md-6 col-md-offset-5">
                            {!! Form::open(array('url'=>'order/saveorsendemail', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'sendsaveFormAjax')) !!}
                            <?php
                            $send_to=\Session::get('send_to');
                            $send_from=\Session::get('eid');
                            $order_id=\Session::get('order_id');
                            ?>
                            <input type="hidden" value="{{ $send_to }}" name="to"/>
                            <input type="hidden" value="{{ $send_from }}" name="from"/>
                            <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                            <input type="hidden" value="" id="opt" name="opt"/>
                            <div class="form-group" style="margin-top:10px;">
                                <a href="{{ URL::to('order/po/'.$order_id)}}" style="width:33%"  class=" btn  btn-lg btn-primary" title="SAVE"><i class="fa fa-save" aria-hidden="true"></i> &nbsp {{ Lang::get('core.sb_save') }}</a>
                           </div>
                            <div class="form-group" style="margin-top:10px;">
                                <a href="{{ URL::to('order/po/'.$order_id)}}" style="width:33%"  class=" btn  btn-lg btn-success" title="SAVE & SEND" id="save_send"><i class="fa  fa-download" aria-hidden="true"></i>  &nbsp {{ Lang::get('core.sb_save_send') }}</a>    </div>

                            <div class="form-group" style="margin-top:10px;">
                                <button type="submit" name="submit" value="sendemail" class="btn btn-info btn-lg"style="width:33%" title="SEND" ><i class="fa fa-sign-in  "></i>&nbsp {{ Lang::get('core.sb_send') }}</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <ul class="parsley-error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
<style>
    .footer
    {
        margin-left:0px!important;

    }
    #page-wrapper{

    }
    </style>
    <script>
      function reloadOrder()
      {
          location.href="{{ url() }}/order";
      }
        $("#save_send").click(function(){
            $('#sendsaveFormAjax').submit();
        });
        var form = $('#sendsaveFormAjax');
        form.parsley();
        form.submit(function () {
            var val=$(document.activeElement).val();
            $("#opt").val(val);
            if(val=="saveandsendemail")
            {
                $test.trigger('click');
            }
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
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        function test()
        {
            alert();
        }
    </script>

@stop