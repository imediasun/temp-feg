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

            {!! Form::open(array('url'=>'ordersetting/save', 'class'=>'form-horizontal', 'parsley-validate'=>'','novalidate'=>' ', 'id'=> 'sbticketSetting')) !!}

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
                                <td>This PO PDF note is a default PO not for Merchandise Orders</td>
                                <td>
                                    <textarea name="merchandisePONote" class="form-control" rows="7"></textarea>
                                </td>
                                <td>
                                    <select name='merchandiseordertypes[]' multiple rows='5' id="merchandiseordertype"
                                            class='select2 '>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <!--<td>1</td>-->
                                <td>None Merchandise Orders PO PDF Notes</td>
                                <td>This PO PDF note is a default PO not for None Merchandise Orders</td>
                                <td>
                                    <textarea name="nonemerchandisePONote" class="form-control" rows="7"></textarea>
                                </td>
                                <td>
                                    <select name='nonemerchandiseordertypes[]' multiple rows='5'
                                            id="nonemerchandiseordertype" class='select2 '>
                                    </select>
                                </td>
                            </tr>

                            {{--<tr>--}}
                            {{--<td>5</td>--}}
                            {{--<td>Able to subscribe to email alerts by ticket</td>--}}
                            {{--<td>--}}
                            {{--<select name='role5[]' multiple id="role5" rows='5' class='select2 '>--}}

                            {{--</select>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<select name='individual5[]' multiple rows='5' id="individual5" class='select2 '>--}}

                            {{--</select>--}}
                            {{--</td>--}}
                            {{--</tr>--}}
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
            superAdmin = {{\App\Models\Core\Groups::SUPPER_ADMIN}};


            $("#individual5").jCombo("{{ URL::to('sbticket/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: '{{ $ticket_setting->individual5 }}'});
        });
        @if(Session::get('gid') != \App\Models\Core\Groups::SUPPER_ADMIN)
                    $(document).ajaxStop(function () {
            console.log("Triggered ajaxStop handler.");
            $('#role1 option[value="' + superAdmin + '"],#role2 option[value="' + superAdmin + '"],#role3 option[value="' + superAdmin + '"],#role4 option[value="' + superAdmin + '"],#role5 option[value="' + superAdmin + '"]').remove();
            /*$('#role1 option[value="'+superAdmin+'"],#role2 option[value="'+superAdmin+'"],#role3 option[value="'+superAdmin+'"],#role4 option[value="'+superAdmin+'"],#role5 option[value="'+superAdmin+'"]').attr('disabled','disabled');*/
            $('#role1,#role2,#role3,#role4,#role5').trigger('change');
        });
                @endif
        var form = $('#sbticketSetting');
        form.parsley();
        form.submit(function () {
            if (form.parsley('isValid') == true) {
                /*$('#role1 option[value="'+superAdmin+'"],#role2 option[value="'+superAdmin+'"],#role3 option[value="'+superAdmin+'"],#role4 option[value="'+superAdmin+'"],#role5 option[value="'+superAdmin+'"]').removeAttr('disabled');
                 $('#role1,#role2,#role3,#role4,#role5').trigger('change');*/
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
