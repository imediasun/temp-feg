@extends('layouts.app')
@section('content')
    <div class="page-content row">
        <!-- Page header -->

        <div class="sbox-title">
            <h3> Save/Download PO
            </h3>
            <button style="visibility: hidden" type="button " class="btn-xs collapse-close btn btn-danger pull-right"><i
                        class="fa fa fa-times"></i></button>
            <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" id="close"
               onclick="reloadOrder();"
                    ><i class="fa fa fa-times"></i></a>
        </div>
        <div class="page-content-wrapper m-t">
            <div class="ajaxLoading"></div>
            <div class="sbox">
                <div class="sbox-content">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-5">
                            {!! Form::open(array('url'=>'order/saveorsendemail', 'class'=>'form-horizontal','files' =>
                            true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'saveFormAjax')) !!}
                            <?php
                            $send_from = \Session::get('eid');
                            $order_id = \Session::get('order_id');
                            ?>
                            <input type="hidden" value="{{ $send_from }}" name="from"/>
                            <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                            <input type="hidden" value="" id="opt" name="opt"/>

                            <div class="form-group" style="margin-top:10px;">
                                <a href="{{ URL::to('order/po/'.$order_id)}}" style="width:33%"
                                   class=" btn  btn-lg btn-primary" title="SAVE"><i class="fa fa-save"
                                                                                    aria-hidden="true"></i>
                                    &nbsp {{ Lang::get('core.sb_save') }}</a>
                            </div>

                            <div class="form-group" style="margin-top:10px;">
                                <button data-toggle="modal" data-button="savesend" type="button" data-target="#savesendModal" style="width:33%"
                                   class=" btn  btn-lg btn-success" title="SAVE & SEND" id="save_send_modal"><i
                                            class="fa  fa-download" aria-hidden="true"></i>
                                    &nbsp {{ Lang::get('core.sb_save_send') }}</button></div>
                        <div class="form-group" style="margin-top:10px;">
                            <button data-button="save" data-toggle="modal" type="button" data-target="#myModal" class="btn btn-info btn-lg"
                                    style="width:33%" title="SEND"><i
                                        class="fa fa-sign-in  "></i>&nbsp {{ Lang::get('core.sb_send') }}</button>
                        </div>
                        </div>
                        {!! Form::close() !!}
                        <ul class="parsley-error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- popup for sending email  -->

                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div id="mycontent" class="modal-content">
                                <div id="myheader" class="modal-header">
                                    <button type="button " class="btn-xs collapse-close btn btn-danger pull-right"
                                            data-dismiss="modal" aria-hidden="true"><i class="fa fa fa-times"></i>
                                    </button>
                                    <h4>Send Email</h4>
                                </div>
                                <div class="modal-body col-md-offset-1 col-md-10">
                                    {!! Form::open(array('url'=>'order/saveorsendemail',
                                    'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>'
                                    ','id'=>'sendFormAjax')) !!}
                                    <?php
                                    $send_from = \Session::get('eid');
                                    $order_id = \Session::get('order_id');
                                    ?>
                                    <input type="hidden" value="{{ $send_from }}" name="from"/>
                                    <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                                    <input type="hidden" value="" id="opt" name="opt"/>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="to">To</label>
                                        <div class="col-md-8">
                                            <select name="to[]" multiple id="to" class="form-control select2"
                                                    required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="cc">CC</label>
                                        <div class="col-md-8">
                                            <select name="cc[]" id="cc" multiple class="form-control select2"
                                                    required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="bcc">BCC</label>
                                        <div class="col-md-8">
                                            <select name="bcc[]" id="bcc" multiple class="form-control select2"
                                                    required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="message">Message</label>

                                        <div class="col-md-8">
                                            <textarea class="form-control" cols="5" rows="6" name="message"
                                                      id="message"/>Purchase Order</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-6 col-md-6">
                                        <div class="form-group" style="margin-top:10px;">
                                            <button type="submit" name="submit" value="sendemail" data-button="create"
                                                    class="btn btn-info btn-lg" style="width:33%" title="SEND"><i
                                                        class="fa fa-sign-in  "></i>&nbsp {{ Lang::get('core.sb_send') }}
                                            </button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="clearfix"></div>

                            </div>

                        </div>
                    </div>
                    <!-- popup for sending email ends here.. -->
                    <!-- pop up for sending and saving starts here.. -->
                    <div class="modal fade" id="savesendModal" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div id="mycontent" class="modal-content">
                                <div id="myheader" class="modal-header">
                                    <button type="button " class="btn-xs collapse-close btn btn-danger pull-right"
                                            data-dismiss="modal" aria-hidden="true"><i class="fa fa fa-times"></i>
                                    </button>
                                    <h4>Send Email</h4>
                                </div>
                                <div class="modal-body col-md-offset-1 col-md-10">
                                    {!! Form::open(array('url'=>'order/saveorsendemail',
                                    'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>'
                                    ','id'=>'sendsaveFormAjax')) !!}
                                    <?php
                                    $send_from = \Session::get('eid');
                                    $order_id = \Session::get('order_id');
                                    ?>
                                    <input type="hidden" value="{{ $send_from }}" name="from"/>
                                    <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                                    <input type="hidden" value="" id="opt" name="opt"/>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="to">To</label>
                                        <div class="col-md-8">
                                            <select name="to[]" multiple id="to1" class="form-control select2"
                                                    required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="cc">CC</label>
                                        <div class="col-md-8">
                                            <select name="cc[]" id="cc1" multiple class="form-control select2"
                                                    required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="bcc">BCC</label>
                                        <div class="col-md-8">
                                            <select name="bcc[]" id="bcc1" multiple class="form-control select2"
                                                    required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="message">Message</label>

                                        <div class="col-md-8">
                                            <textarea class="form-control" cols="5" rows="6" name="message"
                                                      id="message"/>Purchase Order</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-6 col-md-6">
                                        <div class="form-group" style="margin-top:10px;">
                                            <div class="form-group" style="margin-top:10px;">
                                                <a href="{{ URL::to('order/po/'.$order_id)}}"
                                                   class=" btn  btn-lg btn-success" title="SAVE & SEND" id="save_send"><i
                                                            class="fa  fa-download" aria-hidden="true"></i>
                                                    &nbsp {{ Lang::get('core.sb_save_send') }}</a></div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="clearfix"></div>

                            </div>

                        </div>
                    </div>
                    <!-- pop up for sending and saving ends here .. -->
                </div>
            </div>
        </div>
    </div>
    <style>
        .footer {
            margin-left: 0px !important;

        }
        #page-wrapper {

        }
    </style>
    <script>
        $(document).ready(function () {
            $("#to").jCombo("{{ URL::to('order/comboselect?filter=vendor:email:email') }}",
                    {initial_text: 'Select Receipts'});
            $("#cc").jCombo("{{ URL::to('order/comboselect?filter=vendor:email:email') }}",
                    {initial_text: 'Select CC'});
            $("#bcc").jCombo("{{ URL::to('order/comboselect?filter=vendor:email:email') }}",
                    {initial_text: 'Select BCC'});
            $("#to1").jCombo("{{ URL::to('order/comboselect?filter=vendor:email:email') }}",
                    {initial_text: 'Select Receipts'});
            $("#cc1").jCombo("{{ URL::to('order/comboselect?filter=vendor:email:email') }}",
                    {initial_text: 'Select CC'});
            $("#bcc1").jCombo("{{ URL::to('order/comboselect?filter=vendor:email:email') }}",
                    {initial_text: 'Select BCC'});
        });
        function reloadOrder() {
            location.href = "{{ url() }}/order";
        }
        $("#save_send").click(function () {
            $('#sendsaveFormAjax').submit();
        });
        var form = $('#sendsaveFormAjax');
        form.parsley();
        form.submit(function () {
            var val = $(document.activeElement).val();
            $("#opt").val(val);
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
                $('.ajaxLoading').hide();
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }

    </script>

@stop