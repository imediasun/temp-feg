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
                                <td>This PO PDF note is a default PO note for Merchandise Orders.
                                    Place <code>MERCHANDISE_CONTACT</code>, <code>GENERAL_MANAGER</code>, <code>REGIONL_DIRECTOR</code>,
                                    <code>SVP_CONTACT</code>, <code>TECHNICAL_CONTACT</code>, these keywords where you
                                    want to show the email addresses.

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
                                <td>This PO PDF note is a default PO note for Non Merchandise Orders.
                                    Place <code>MERCHANDISE_CONTACT</code>, <code>GENERAL_MANAGER</code>, <code>REGIONL_DIRECTOR</code>,
                                    <code>SVP_CONTACT</code>, <code>TECHNICAL_CONTACT</code>, these keywords where you
                                    want to show the email addresses.
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

                                <td>New graphics request conent for sender</td>
                                <td>
                                    <textarea name="newgraphicsrequestsendercontent" class="form-control"
                                              rows="7"></textarea>
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <!--<td>1</td>-->
                                <td>New Graphics Request Content For Receiver</td>

                                <td>New graphics request conent for receiver</td>
                                <td>
                                    <textarea name="newgraphicsrequestreceivercontent" class="form-control"
                                              rows="7"></textarea>
                                </td>
                                <td>

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
    </script>
@stop
