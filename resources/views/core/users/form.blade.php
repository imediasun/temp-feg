@extends('layouts.app')

@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> {{ $pageTitle }}
                    <small>{{ $pageNote }}</small>
                </h3>
            </div>
            <ul class="breadcrumb">
                <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
                <li><a href="{{ URL::to('core/users?return='.$return) }}">{{ $pageTitle }}</a></li>
                <li class="active">{{ Lang::get('core.addedit') }} </li>
            </ul>

        </div>

        <div class="page-content-wrapper m-t">


            <div class="sbox animated fadeInRight">
                <div class="sbox-title"></div>
                <div class="sbox-content">
                    <ul class="parsley-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    {!! Form::open(array('url'=>'core/users/save?return='.$return, 'id'=>'user_form','class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
                    <div class="col-md-6">


                        <div class="form-group hidethis " style="display:none;">
                            <label for="Id" class=" control-label col-md-4 text-left"> Id </label>
                            <div class="col-md-6">
                                {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Group / Level" class=" control-label col-md-4 text-left"> Group / Level <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                <select name='group_id' rows='5' id='group_id' code='{$group_id}'
                                        class='select2 ' required></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Username" class=" control-label col-md-4 text-left"> Username <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                {!! Form::text('username', $row['username'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="First Name" class=" control-label col-md-4 text-left"> First Name <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                {!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Last Name" class=" control-label col-md-4 text-left"> Last Name </label>
                            <div class="col-md-6">
                                {!! Form::text('last_name', $row['last_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Email" class=" control-label col-md-4 text-left"> Email <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'email'   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>

                        <div class="form-group  ">
                            <label for="Status" class=" control-label col-md-4 text-left"> Status <span class="asterix"> * </span></label>
                            <div class="col-md-6">

                                <label class='radio radio-inline'>
                                    <input type='radio' name='active' value='0' required
                                           @if($row['active'] == '0') checked="checked" @endif > Inactive </label>
                                <label class='radio radio-inline'>
                                    <input type='radio' name='active' value='1' required
                                           @if($row['active'] == '1') checked="checked" @endif > Active </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="multiple_loc" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Locations', (isset($fields['locations']['language'])? $fields['assign_to']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='multiple_locations[]' multiple rows='5' id='multiple_loc' class='select2'
                                        required="required"></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="has_all_locations" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('All Locations', (isset($fields['has_all_locations']['language'])? $fields['has_all_locations']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <input type="checkbox" name="all_locations" value="1" id="has_all_locations"
                                       @if(isset($row['has_all_locations']) && $row['has_all_locations'] != 0 ) checked
                                       @endif  class="form-control"/>


                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>

                        <div class="form-group  ">
                            <label for="Avatar" class=" control-label col-md-4 text-left"> Avatar </label>
                            <div class="col-md-6">
                                <input type='file' name='avatar' id='avatar' @if($row['avatar'] =='')
                                       @endif style='width:350px !important;' value="{{ $row['avatar'] }}"/>
                                <div>
                                    {!! SiteHelpers::showUploadedFile($row['avatar'],'/uploads/users/') !!}

                                </div>

                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="ipt" class=" control-label col-md-4 text-left"> </label>
                            <div class="col-md-8">
                                @if($row['id'] !='')
                                    {{ Lang::get('core.notepassword') }}
                                @else
                                    Create Password
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="ipt"
                                   class=" control-label col-md-4"> {{ Lang::get('core.newpassword') }} </label>
                            <div class="col-md-8">
                                <input name="password" type="password" id="password" class="form-control input-sm"
                                       value=""
                                       @if($row['id'] =='')
                                       required
                                        @endif
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ipt"
                                   class=" control-label col-md-4"> {{ Lang::get('core.conewpassword') }} </label>
                            <div class="col-md-8">
                                <input name="password_confirmation" type="password" id="password_confirmation"
                                       class="form-control input-sm" value=""
                                       @if($row['id'] =='')
                                       required
                                        @endif
                                />
                            </div>
                        </div>

                        <div class="form-group  int-link">
                            <label for="ipt" class=" control-label col-md-4 text-right">Login Start Page </label>
                            <div class="col-md-8">
                                <select name="redirect_link" rows='5' type="text" id="redirect_link" style="width:100%"
                                        class='select-liquid ' value="{{ $row['redirect_link'] }}">
                                    <option value=""> -- Select Module or Page --</option>
                                    <optgroup label="Module ">
                                        @foreach($modules as $mod)
                                            <?php
                                                $moduleConfig = \SiteHelpers::CF_decode_json($mod->module_config);
                                                $moduleRoute = $mod->module_name;
                                                if (isset($moduleConfig['setting']['module_route'])) {
                                                    $moduleRoute = $moduleConfig['setting']['module_route'];
                                                }
                                                $modulePublicAccess = isset($moduleConfig['setting']['publicaccess'])?
                                                        $moduleConfig['setting']['publicaccess']:true;
                                            ?>
                                            @if($modulePublicAccess)
                                            <option value="{{ $moduleRoute }}"
                                                @if($row['redirect_link'] === $moduleRoute )   selected="selected" @endif
                                                >{{ $mod->module_title}}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    {{--<optgroup label="Dashboards">
                                        <option value="dashboard">Dashboard</option>
                                    </optgroup>--}}
                                    <optgroup label="Page CMS">
                                        @foreach($pages as $page)
                                            <option value="{{ $page->alias}}"
                                                    @if($row['redirect_link']=== $page->alias ) selected="selected" @endif
                                            >Page : {{ $page->title}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>


                        {{--<div class="form-group">
                            <label for="ipt"
                                   class=" control-label col-md-4"> {{ Lang::get('core.g_mail') }} </label>
                            <div class="col-md-8">
                                <input name="g_mail" type="text" id="g_mail" class="form-control input-sm" value="{{$row['g_mail']}}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ipt"
                                   class=" control-label col-md-4"> {{ Lang::get('core.g_password') }} </label>
                            <div class="col-md-8">
                                <input name="g_password" type="password" id="g_password" class="form-control input-sm" value="" />
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <p class="bg-info" style="padding: 5px">{!! Lang::get('core.gmail_smtp_connect_failed') !!}</p>
                            </div>
                        </div>--}}
                        @if($row['id'] !='')
                            <div class="form-group">

                                <label for="ipt" class=" control-label col-md-4 text-left"> </label>
                                <div class="col-md-8">
                                    Google Account Info
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ipt"
                                       class=" control-label col-md-4"> Connect With Gmail </label>
                                <div class="col-md-8">
                                    @if($row['oauth_token'])
                                        <button type="button" disabled
                                                class="btn btn-success btn-sm connectGmail">
                                            <i class="fa  fa-check-circle "></i> Connected
                                        </button>
                                        <a href="{{$oauth_url}}" class="connectGmail btn" style="background-color:#DD4B39; border-color: #DD4B39; color: #ffffff;padding: 5px 8.5px;"><i class="icon-google-plus"></i> Reconnect </a>
                                    @else
                                        <a href="{{$oauth_url}}" class="connectGmail btn btn-block" style="background-color:#DD4B39; border-color: #DD4B39; color: #ffffff;"><i class="icon-google-plus"></i> Google </a>
                                       {{-- <button type="button" onclick="location.href='{{$oauth_url}}' "
                                                class="btn btn-success btn-sm connectGmail">
                                            <i class="fa  fa-check-circle-o "></i> Connect
                                        </button>--}}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>


                    <div style="clear:both"></div>


                    <div class="form-group">
                        <label class="col-sm-4 text-right">&nbsp;</label>
                        <div class="col-sm-8">
                            <button type="submit" name="apply" class="btn btn-info btn-sm"><i
                                        class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
                            <button type="submit" name="submit" class="btn btn-primary btn-sm"><i
                                        class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
                            <button type="button" onclick="location.href='{{ URL::to('core/users?return='.$return) }}' "
                                    class="btn btn-success btn-sm "><i
                                        class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }}
                            </button>
                        </div>

                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[type=checkbox]').click(function () {
                if (this.checked) {
                    $(this).prev().val('1');
                }
                else {
                    $(this).prev().val('0');
                }
            });

            $("#group_id").jCombo("{{ URL::to('core/users/comboselect?filter=tb_groups:group_id:name') }}",
                    {selected_value: '{{ $row["group_id"] }}'});
            $("#multiple_loc").jCombo("{{ URL::to('core/users/comboselect?filter=location:id:location_name') }}",
                    {selected_value: '{{ $user_locations }}'});
        });

        $('#has_all_locations').on('ifChecked', function () {


// Deactivate Parsley validation
            $('#user_form').parsley().destroy();

// Make your field not required, thus disabling validation for it
            $('#multiple_loc').removeAttr('required');

// Reactivate Parsley validation
            $('#user_form').parsley({
                //options
            });

        });

        // For onUncheck callback
        $('#has_all_locations').on('ifUnchecked', function () { //Do your code
            // Deactivate Parsley validation
            $('#user_form').parsley().destroy();

// Make your field required by adding the required attribute back to the element
            $('#multiple_loc').attr('required', '');

// Reactivate Parsley validation
            $('#user_form').parsley({
                //options
            });
        });
    </script>
@stop
