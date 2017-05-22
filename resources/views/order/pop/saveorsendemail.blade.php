<?php use App\Models\Order; ?>
<div class="m-t orderSaveSend ">
    <div class="sbox">
        <div class="sbox-title">
            <h3> Save/Download PO</h3>
            <a href="javascript:void(0)"
               class="collapse-close pull-right btn btn-xs btn-danger"
               id="closeSaveOrSend"
            ><i class="fa fa fa-times"></i></a>
        </div>
        <div class="sbox-content">
            <div style="color:green" class="row">
                <?php
                $order_id = \Session::get('order_id');
                $send_to  = \Session::get('eid');
                $vendor_email = \Session::get('send_to');
                    if(!empty($vendor_email))
                        {
                $send_to .=','.$vendor_email;
                        }
                ?>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-5">
                    {!! Form::open(array('url'=>'order/saveorsendemail', 'class'=>'form-horizontal','files' =>
                    true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'saveFormAjax')) !!}
                    <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                    <input type="hidden" value="{{$send_to}}" name="to"/>
                    <input type="hidden" value="" name="message" id="save_message"/>
                    <div class="form-group" style="margin-top:10px;">
                        <a href="{{ URL::to('order/po/'.$order_id)}}" id="po-link" style="width:33%"
                           class=" btn  btn-lg btn-primary" title="SAVE" data-action="save" ><i class="fa fa-save"
                                                                            aria-hidden="true"></i>
                            &nbsp {{ Lang::get('core.sb_save') }}</a>
                        <a href="javascript:void(0)" id="po-close" style="width:33%;display: none"
                           class=" btn  btn-lg btn-primary" title="Close" data-action="close" >&nbsp {{ Lang::get('core.sb_close') }}</a>
                    </div>

                    <div class="form-group" style="margin-top:10px;">
                        <button  data-button="savesend"
                                     type="button"  data-mode="gmail-compose" data-target="#savesendModal" data-toggle="modal"  style="width:33%"
                                     class=" btn  btn-lg btn-success" title="SAVE & SEND"  id="save_send_modal"><i
                                        class="fa  fa-download" aria-hidden="true"></i>
                                &nbsp {{ Lang::get('core.sb_save_send') }}</button>
                    </div>
                    <div class="form-group" style="margin-top:10px;">
                        <button data-button="save"  data-toggle="modal"  data-target="#myModal" data-mode="gmail-compose"  id="send-only" type="button"
                                class="btn btn-info btn-lg "
                                style="width:33%" title="SEND" ><i
                                    class="fa fa-sign-in  "></i>&nbsp {{ Lang::get('core.sb_send') }} </button>
                    </div>
                    
                    @if($order_id && Order::isApiable($order_id) && !Order::isApified($order_id))
                    <div class="form-group" style="margin-top:10px;">
                        <button type="button" class="btn btn-info btn-lg exposeAPIFromSaveOrSend"
                                style="width:33%">
                        {{ Lang::get('core.order_api_expose_button_label') }} </button>
                    </div>
                    {!! Form::close() !!}
                    @endif
                </div>
                <ul class="parsley-error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- popup for sending email  -->

            <div class="modal" id="myModal" role="dialog">
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

                            <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                            <input type="hidden" value="" id="opt" name="opt"/>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="to">To</label>

                                <div class="col-md-8">
                                    <input name="to" value="{{ $send_to }}"  id="to" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="cc">CC</label>
                                <div class="col-md-8">
                                    <input name="cc" id="cc" value="{{ $cc }}" multiple class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="bcc">BCC</label>

                                <div class="col-md-8">
                                    <input name="bcc" id="bcc" multiple class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="message">Message</label>

                                <div class="col-md-8">
                                    <textarea class="form-control" cols="5" rows="6" name="message"
                                              id="message"/>Purchase Order</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="type" value="send"/>
                            <div class="col-md-offset-6 col-md-6">
                                <div class="form-group" style="margin-top:10px;">
                                    <button type="button" name="submit" value="sendemail" id="send-email"
                                            data-button="create"
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
            <div class="modal" id="savesendModal" role="dialog">
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

                            <input type="hidden" value="{{ $order_id }}" name="order_id"/>
                            <input type="hidden" value="" id="opt" name="opt"/>
                            <input type="hidden" value="sendorsave" name="submit"/>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="to">To</label>

                                <div class="col-md-8">
                                    <input name="to1" value="{{ $send_to }}"  id="to1" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="cc">CC</label>

                                <div class="col-md-8">
                                    <input name="cc1" id="cc1" value="{{ $cc }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="bcc">BCC</label>

                                <div class="col-md-8">
                                    <input name="bcc1" id="bcc1" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="message">Message</label>

                                <div class="col-md-8">
                                    <textarea class="form-control" cols="5" rows="6" name="message"
                                              id="message1"/>Purchase Order</textarea>
                                </div>
                                <input type="hidden" name="type" value="sendorsave"/>
                            </div>
                            <div class="col-md-offset-6 col-md-6">
                                <div class="form-group" style="margin-top:10px;">
                                    <div class="form-group" style="margin-top:10px;">
                                        <button type="submit" name="submit" class=" btn  btn-lg btn-success" title="SAVE & SEND" id="save_send"> <i class="fa  fa-download" aria-hidden="true"></i> &nbsp {{ Lang::get('core.sb_save_send') }}</button>
                                    </div>
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
    <script>

    (function () {


        $(document).ready(function () {

            $(".exposeAPIFromSaveOrSend").click(function(e){
                var btn = $(this);
                btn.prop('disabled', true);
                blockUI();
                $.ajax({
                    type: "GET",
                    url: "{{ url() }}/order/expose-api/{{ \SiteHelpers::encryptID($order_id) }}",
                    success: function (data) {
                        unblockUI();
                        if(data.status === 'success'){
                            notyMessage(data.message);
                            btn.remove();
                        }
                        else {
                            btn.prop('disabled', false);
                            notyMessageError(data.message);
                        }
                    }
                });
            });
        });
        $("#po-close, a.#closeSaveOrSend").click(function(e){
            reloadOrder();
        });
        $("#po-link").click(function () {
            $(this).hide();
            $('#po-close').show();
        });
        $("#to1").click(function () {
            var to1 = $(this).val();
            if (to1 != null) {
                $("#save_send").removeAttr('disabled');
            }
            else {
                $("#save_send").attr('disabled', 'disabled');
            }
        });
        $("#to").click(function () {
            if ($(this).val() != null) {
                $("#send-email").removeAttr("disabled")
            }
            else {
                $("#send-email").attr("disabled", true);
            }
        });


        function reloadOrder() {

            var moduleUrl = '{{ $pageUrl }}',
                redirect = "{{ \Session::get('redirect') }}",
                redirectLink = "{{ url() }}/" + redirect;

            if (/order/i.test(redirect)) {
                $('.ajaxLoading').hide();
                SximoModalHide($('.modal'));
                ajaxViewClose("#order", null, {noModal: true});
            }
            else {
//            {{ \Session::put('filter_before_redirect','redirect') }}
                location.href = redirectLink;
            }

        }
        $("#send-only").click(function (e) {
            $('.ajaxLoading').show();
            var send_to = "{{ $send_to }}" != " " && "{{ $send_to }}";
            var mode = $(this).data('mode');
            emailSending(send_to, mode);
            if (!send_to && !$("#to1").val()) {
                $("#save_send").attr('disabled', 'disabled');
            }
        });
        $("#save_send_modal").click(function () {
            $('.ajaxLoading').show();
            var send_to = "{{ $send_to }}" != " " && "{{ $send_to }}";
            var mode = $(this).data('mode');
            emailSending(send_to, mode);
            if (!send_to && !$("#to1").val()) {
                $("#save_send").attr('disabled', 'disabled');
            }
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
        var saveForm = $('#saveFormAjax');
        saveForm.parsley();
        saveForm.submit(function () {
            var val = $(document.activeElement).val();
            $("#opt").val(val);
            if (saveForm.parsley('isValid') == true) {
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
        var sendForm = $('#sendFormAjax');
        sendForm.parsley();
        sendForm.submit(function () {
            var val = $(document.activeElement).val();
            $("#opt").val(val);
            if (sendForm.parsley('isValid') == true) {
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
                reloadOrder();
                //$('#sximo-modal').modal('hide');
                //$('body').removeClass('modal-open');
                //$('.modal-backdrop').remove();
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                //do not reload order page after error
                //reloadOrder();
            }
        }
        function emailSending(send_to,mode)
        {
            $.get("{{ url() }}/order/po/{{ $order_id }}?mode=save", function (data, status) {
                $('.ajaxLoading').hide();
                if (mode == "gmail-account") {
                    $("#save_message").val(data['url'] + "/order/download-po/" + data['file_name']);
                    $('#saveFormAjax').submit();
                }
                else {
                    $("#message,#message1").text(data['url'] + "/order/download-po/" + data['file_name']);
                    $('#send-email').click(function () {
                        $("#sendFormAjax").submit();
                    });
                    $('#save_send').click(function () {
                        window.location.href="{{ URL::to('order/po/'.$order_id)}}";
                    });
                }
            });
        }
    }());
    </script>
