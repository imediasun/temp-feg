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

            {!! Form::open(array('url'=>'sbticket/savepermission', 'class'=>'form-horizontal', 'parsley-validate'=>'','novalidate'=>' ', 'id'=> 'sbticketSetting')) !!}

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
                            <th field="name3" width="30%">Roles</th>
                            <th field="name4" width="40%">Individuals</th>

                        </tr>
                        </thead>
                        <tbody class="no-border-x no-border-y">
                        <tr>
                            <!--<td>1</td>-->
                            <td>View All Tickets</td>
                            <td>Users and/or User Groups assigned to this category will be able to see ALL tickets from ALL locations, including locations not assigned to that user.</td>
                            <td>
                                <select name='role1[]' multiple id="role1" rows='5' class='select2 '>
                                </select>
                            </td>
                            <td>
                                <select name='individual1[]' multiple rows='5' id="individual1" class='select2 '>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <!--<td>2</td>-->
                            <td>All Email Notifications</td>
                            <td>Users and/or User Groups assigned to this category will receive ALL email notifications for tickets that have been created in a location to which they have been assigned.</td>
                            <td>
                                <select name='role2[]' multiple id="role2" rows='5' class='select2 '>
                                </select>
                            </td>
                            <td>
                                <select name='individual2[]' multiple rows='5' id="individual2" class='select2 '>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <!--<td>4</td>-->
                            <td>Receive 1st Email Notifications</td>
                            <td>Users and/or User Groups assigned to this category will only receive the 1st email notification when a ticket is created in a location to which they have been assigned.</td>
                            <td>
                                <select name='role4[]' multiple id="role4" rows='5' class='select2 '>
                                </select>
                            </td>
                            <td>
                                <select name='individual4[]' multiple rows='5' id="individual4" class='select2 '>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <!--<td>3</td>-->
                            <td>Can change status</td>
                            <td>Users and/or User Groups assigned to this category will be able to change ticket status.</td>
                            <td>
                                <select name='role3[]' multiple id="role3" class='select2 '>
                                </select>
                            </td>
                            <td>
                                <select name='individual3[]' multiple id="individual3" class='select2 '>
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
                    <button type="submit" class="btn btn-success"> Save Changes </button>
                </div>	</div>
            {!! Form::close() !!}


        </div>	</div>

    <script>
        $(document).ready(function(){
            superAdmin = {{\App\Models\Core\Groups::SUPPER_ADMIN}};
            $("#role1").jCombo("{{ URL::to('sbticket/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: '{{ $ticket_setting->role1 }}'});
            $("#role2").jCombo("{{ URL::to('sbticket/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: '{{ $ticket_setting->role2 }}'});
            $("#role3").jCombo("{{ URL::to('sbticket/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: '{{ $ticket_setting->role3 }}'});
            $("#role4").jCombo("{{ URL::to('sbticket/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: '{{ $ticket_setting->role4 }}'});
            $("#role5").jCombo("{{ URL::to('sbticket/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: '{{ $ticket_setting->role5 }}'});
            $("#individual1").jCombo("{{ URL::to('sbticket/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: '{{ $ticket_setting->individual1 }}'});
            $("#individual2").jCombo("{{ URL::to('sbticket/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: '{{ $ticket_setting->individual2 }}'});
            $("#individual3").jCombo("{{ URL::to('sbticket/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: '{{ $ticket_setting->individual3 }}'});
            $("#individual4").jCombo("{{ URL::to('sbticket/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: '{{ $ticket_setting->individual4 }}'});
            $("#individual5").jCombo("{{ URL::to('sbticket/comboselect?filter=users:id:first_name|last_name') }}",
                    {selected_value: '{{ $ticket_setting->individual5 }}'});
        });
        @if(Session::get('gid') != \App\Models\Core\Groups::SUPPER_ADMIN)
                    $( document ).ajaxStop(function() {
            console.log( "Triggered ajaxStop handler." );
            $('#role1 option[value="'+superAdmin+'"],#role2 option[value="'+superAdmin+'"],#role3 option[value="'+superAdmin+'"],#role4 option[value="'+superAdmin+'"],#role5 option[value="'+superAdmin+'"]').remove();
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
