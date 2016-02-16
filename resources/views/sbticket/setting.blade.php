@extends('layouts.app')

@section('content')
    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Permission Editor: <small> Edit ticket permission </small></h3>
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

            {!! Form::open(array('url'=>'#', 'class'=>'form-horizontal')) !!}

            <div class="sbox">
                <div class="sbox-title"><h5> Ticket Permission </h5></div>
                <div class="sbox-content">
                    <table class="table table-striped table-bordered" id="table">
                        <thead class="no-border">
                        <tr>
                            <th field="name1" width="20">No</th>
                            <th field="name2" width="20">Section </th>
                            <th field="name3" width="40">Roles</th>
                            <th field="name4" width="40">individuals</th>

                        </tr>
                        </thead>
                        <tbody class="no-border-x no-border-y">
                            <tr>
                                <td>1</td>
                                <td>Able to see all tickets</td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                            @foreach($roles as $role)
                                                <option value="{{ $role->group_id }}">{{ $role->name }}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($individuals as $individual)var_dump($individual);die;
                                            <option value="{{ $individual->id }}">{{ $individual->first_name.' '.$individual->last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Able to receive an email notifications</td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->group_id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($individuals as $individual)var_dump($individual);die;
                                        <option value="{{ $individual->id }}">{{ $individual->first_name.' '.$individual->last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Able to see only assign tickets</td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->group_id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($individuals as $individual)var_dump($individual);die;
                                        <option value="{{ $individual->id }}">{{ $individual->first_name.' '.$individual->last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Able to receive a copy of first email</td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->group_id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($individuals as $individual)var_dump($individual);die;
                                        <option value="{{ $individual->id }}">{{ $individual->first_name.' '.$individual->last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Able to subscribe to email alerts by ticket</td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->group_id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name='' multiple rows='5' class='select2 '>

                                        @foreach($individuals as $individual)var_dump($individual);die;
                                        <option value="{{ $individual->id }}">{{ $individual->first_name.' '.$individual->last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>	</div>
            {!! Form::close() !!}


        </div>	</div>

    <script>
        $(document).ready(function(){

            $(".checkAll").click(function() {
                var cblist = $(this).attr('rel');
                var cblist = $(cblist);
                if($(this).is(":checked"))
                {
                    cblist.prop("checked", !cblist.is(":checked"));
                } else {
                    cblist.removeAttr("checked");
                }

            });
        });
    </script>
@stop