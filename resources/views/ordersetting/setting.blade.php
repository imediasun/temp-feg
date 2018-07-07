@extends('layouts.app')

@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Setting </h3>
            </div>

            <ul class="breadcrumb">
                <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
                <li class="active"> Ticket Roles</li>
            </ul>

        </div>
        <div class="page-content-wrapper m-t">
            @if(Session::has('message'))
                {{ Session::get('message') }}
            @endif

            {!! Form::open(array('url'=>'ordersetting/save', 'class'=>'form-horizontal', 'parsley-validate'=>'','novalidate'=>' ', 'id'=> 'orderSetting')) !!}

            <div class="sbox">
                <div class="sbox-title"><h5> Setting </h5></div>
                <div class="sbox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered no-white-space" id="table">
                            <thead class="no-border">
                            <tr>
                                <!--                            <th field="name1" width="5%">No</th>-->
                                <th field="name2" width="10%">Title</th>
                                <th field="name2" width="20%">Description</th>
                                <th field="name3" width="30%">PO Note</th>
                                <th field="name4" width="40%">Order Types</th>

                            </tr>
                            </thead>
                            <tbody class="no-border-x no-border-y">
                            <tr>
                                <!--<td>1</td>-->
                                <td>Merchandise Orders PO PDF Notes</td>
                                <td>
                                    This is the default PO Note which appears on the PO PDF. To include the location's listing for a specific job position, please use one of the following tags in your note text:
                                    <br> <code>MERCHANDISE_CONTACT</code><br>
                                    <code>GENERAL_MANAGER</code><br>
                                    <code>REGIONAL_DIRECTOR</code><br>
                                    <code>SVP_CONTACT</code><br>
                                    <code>TECHNICAL_CONTACT</code><br>
                                    The tag above will be swapped out for whichever Employee is listed in that position in the Locations module. If no employee is listed in a position and that position's tag is used, then no name will appear in the PDF Note.
                                </td>
                                <td>
                                    <textarea name="merchandisePONote" class="form-control"
                                              rows="7">{{ $MerchandisePO }}</textarea>
                                </td>
                                <td>
                                    <select name='merchandiseordertypes[]' multiple rows='5' id="merchandiseordertype"
                                            class='select2 '>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <!--<td>1</td>-->
                                <td>Non Merchandise Orders PO PDF Notes</td>
                                <td>This is the default PO Note which appears on the PO PDF. To include the location's listing for a specific job position, please use one of the following tags in your note text:
                                    <br> <code>MERCHANDISE_CONTACT</code><br>
                                    <code>GENERAL_MANAGER</code><br>
                                    <code>REGIONAL_DIRECTOR</code><br>
                                    <code>SVP_CONTACT</code><br>
                                    <code>TECHNICAL_CONTACT</code><br>
                                    The tag above will be swapped out for whichever Employee is listed in that position in the Locations module. If no employee is listed in a position and that position's tag is used, then no name will appear in the PDF Note.
                                </td>
                                <td>
                                    <textarea name="NonmerchandisePONote" class="form-control"
                                              rows="7">{{ $NonMerchandisePO }}</textarea>
                                </td>
                                <td>
                                    <select name='Nonmerchandiseordertypes[]' multiple rows='5'
                                            id="Nonmerchandiseordertype" class='select2 '>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <!--<td>1</td>-->
                                <td>New Graphics Request Content For Sender</td>

                                <td>Graphic request email content for person who initiated graphic request.</td>
                                <td>
                                    <textarea name="newgraphicsrequestsendercontent" class="form-control"
                                              rows="7">{{ $GraphicsSender }}</textarea>
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <!--<td>1</td>-->
                                <td>New Graphics Request Content For Receiver</td>

                                <td>Graphic request email content for graphic managers to approve/deny request.</td>
                                <td>
                                    <textarea name="newgraphicsrequestreceivercontent" class="form-control"
                                              rows="7">{{ $GraphicsReceiver }}</textarea>
                                </td>
                                <td>

                                </td>
                            </tr>
                            @if (!empty($fegSettings))
                                @foreach($fegSettings as $settingItem)
                                    <tr>
                                        <td>{!! $settingItem['option_title'] !!}</td>
                                        <td>{!! $settingItem['option_description'] !!}</td>
                                        <td>
                                            <input type="number" min="0" class="form-control"
                                                name="{!! $settingItem['option_name'] !!}"
                                                value="{!! $settingItem['option_value'] !!}"
                                            />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td>{!! $settingItem['option_title'] !!}</td>
                                <td>{!! $settingItem['option_description'] !!}</td>
                                <td>
                                    <select id="excluded_orders" name="excluded_orders[]" class="select2" multiple="multiple"  >
                                        @foreach($Orders as $key => $order)
                                            <option value="{{$key}}" {{in_array($order, $ExcludedOrders) ? 'selected': ''}}>{{$order}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="last_td">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success"> Save Changes</button>
                </div>
            </div>
            {!! Form::close() !!}


        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#merchandiseordertype").jCombo("{{ URL::to('ordersetting/comboselect?filter=order_type:id:order_type') }}",
                    {selected_value: '{{ $MerchandiseOrder }}', initial_text: '-------- Select Order Type --------'});
            $("#Nonmerchandiseordertype").jCombo("{{ URL::to('ordersetting/comboselect?filter=order_type:id:order_type') }}",
                    {
                        selected_value: '{{ $NonMerchandiseOrder }}',
                        initial_text: '-------- Select Order Type --------'
                    });
        });

        var form = $('#orderSetting');
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

            $('.ajaxLoading').hide();
            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}');
                //ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/setting');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
            }
            return false;
        }

        var intervalCount   = 0;
        var interval        = 0;

        $(document).on('keyup','.select2-choices .select2-search-field #s2id_autogen3', function(){

                // intervalCount++;
                //
                // if(intervalCount == 3){
                    var poNumber = $(this).val();
                    $.ajax({
                        url: '/order-setting/search-the-order-by-po-number?po_number='+poNumber,
                        method: 'GET',
                        success: function(result){

                            var selectbox = $('#excluded_orders');
                            selectbox.empty();
                            $.each(result.searchedPONumbers, function(key, val){
                                selectbox.append('<option  val="'+val+'">'+val+'</option>');
                            });
                            $(this).trigger("click");
                            setTimeout(function () {
                            //     $('#s2id_excluded_orders').removeClass('select2-container-active')
                            //     $('#s2id_excluded_orders').addClass('select2-container-active')
                                $('#last_td').trigger('click');
                                selectbox.trigger('click');
                                // $('.select2-choices .select2-search-field #s2id_autogen3').trigger('focusin');
                            }, 200);
                        }
                    });

                //     intervalCount=0;
                // }
        });


        // $('#excluded_orders').on('select', function(){
        //     $('.select2-choices .select2-search-field #s2id_autogen3').val('');
        // })
    </script>
@stop
