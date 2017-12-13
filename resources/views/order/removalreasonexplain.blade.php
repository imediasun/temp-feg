@extends('layouts.app')

@section('content')
    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Order Removal Reason </h3>
            </div>
        </div>

        <div class="page-content-wrapper m-t">


            <div class="sbox animated fadeInRight">
                <div class="sbox-title"><h4><i class="fa fa-table"></i> Order Removal Reason </h4></div>
                <div class="sbox-content">
                    @if(!empty($msgstatus) && $msgstatus=='error')
                        <div class="alert alert-danger" id="alert-remove-after-5-sec">
                            {!! $messagetext !!}
                        </div>
                        <script>
                            $(function(){
                                setTimeout(function(){ $("#alert-remove-after-5-sec").slideUp('slow'); },5000);
                            });
                        </script>
                    @endif
                    <div class="row">
                        <div class="col-md-8" style="">
                            <h1>Order Removal Reason</h1>
                        </div>


                        <div class="col-md-7" style="margin-left:16px;">

                            {!! Form::open(array('url'=>'order/delete', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>'removalreasonform','onsubmit'=>'return validateReason()')) !!}
                            <?php $po_numbers = explode(",", $ids); ?>
                            @foreach($po_numbers as $po_number)
                                <div id="txtarea_{{ $po_number }}" class="form-group">
                                    <label>Order PO Number {{ $po_number }}</label>
                                    <textarea onkeyup="removeErrorMessage(this,'txtarea_{{ $po_number }}');"
                                              parent_id="txtarea_{{ $po_number }}" minlength="10" min="10" rows="9"
                                              name="explaination[]" id="explaination_{{ $po_number }}"
                                              class="explaination"
                                              placeholder="Please Explain Why?"
                                              style="width: 100%;"
                                              required></textarea>
                                    <input type="hidden" value="{{ $po_number }}" name="po_number[]"/>
                                </div>
                            @endforeach
                            <div class="form-group" style="text-align: center;">
                                <button type="submit" name="submit" class="btn btn-primary btn-sm"><i
                                            class="fa  fa-save "></i> Submit
                                </button>
                                <button type="button" onclick="location.href='{{ URL::to('order?return='.$return) }}' "
                                        class="btn btn-success btn-sm "><i
                                            class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }}
                                </button>

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
        $(document).ready(function () {
            var form = $('#removalrequestform');
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
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
                location.href = '{{ url() }}' + '/order';

            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        function removeErrorMessage(obj, div) {
            if ((obj.value).length > 0) {
                $("#" + div + " .lengtherrors").remove();
            }
        }
        function validateReason() {

            var errors = false;
            $(".explaination").each(function (i) {
                if (($(this).val()).length < 10) {
                    var parent_id = $(this).attr('parent_id');
                    var html = '<ul id="parsley-15829651775452813' + i + '" class="parsley-error-list lengtherrors"><li class="required" style="display: list-item;">At least 10 characters must be entered.</li></ul>';
                    $("#" + parent_id).append(html);
                    errors = true;
                }
            });
            if (errors == false) {
                return true;
            } else {
                return false;
            }
        }
    </script>



@stop