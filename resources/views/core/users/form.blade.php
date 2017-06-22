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
                <div class="sbox-title">
                    <h4>
                        @if($id)
                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Employee
                        @else
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Employee
                        @endif
                    </h4>
                </div>
                <div class="sbox-content">
                   {{-- <ul class="parsley-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>--}}

                    {!! Form::open(array('url'=>'core/users/save?return='.$return, 'id'=>'user_form','class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
                    <div class="col-md-6">


                        <div class="form-group hidethis " style="display:none;">
                            <label for="Id" class=" control-label col-md-4 text-left"> Id: </label>
                            <div class="col-md-6">
                                {!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Group / Level" class=" control-label col-md-4 text-left"> Group / Level: <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                <select name='group_id' rows='5' id='group_id' code='{$group_id}' class='select2 ' required></select>
                            </div>

                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Username" class=" control-label col-md-4 text-left"> Username: <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                {!! Form::text('username', $row['username'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="First Name" class=" control-label col-md-4 text-left"> First Name: <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                {!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Last Name" class=" control-label col-md-4 text-left"> Last Name: </label>
                            <div class="col-md-6">
                                {!! Form::text('last_name', $row['last_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Email" class=" control-label col-md-4 text-left"> Email: <span
                                        class="asterix"> * </span></label>
                            <div class="col-md-6">
                                {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'email'   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>

                        <div class="form-group  ">
                            <label for="Status" class=" control-label col-md-4 text-left"> Status: <span class="asterix"> * </span></label>
                            <div class="col-md-6" style="padding-bottom: 15px;">

                                <label class='radio radio-inline'>
                                    <input type='radio' name='active' value='0' required
                                           @if($row['active'] == '0') checked="checked" @endif > Inactive </label>
                                <label class='radio radio-inline'>
                                    <input type='radio' name='active' value='1'
                                           @if($row['active'] == '1') checked="checked" @endif > Active </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="multiple_loc" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Locations:', (isset($fields['locations']['language'])? $fields['assign_to']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='multiple_locations[]' multiple rows='5' id='multiple_loc' class='select2'></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="has_all_locations" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('All Locations:', (isset($fields['has_all_locations']['language'])? $fields['has_all_locations']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <input type="checkbox" name="all_locations" value="1" id="has_all_locations"
                                       @if(isset($row['has_all_locations']) && $row['has_all_locations'] != 0 ) checked
                                       @endif  class="form-control"/>


                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Tier" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Tier', (isset($fields['tier']['language'])? $fields['tier']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::number('tier', $row['tier'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="City" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Primary Phone', (isset($fields['primary_phone']['language'])? $fields['primary_phone']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('primary_phone', $row['primary_phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="City" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Secondary Phone', (isset($fields['secondary_phone']['language'])? $fields['secondary_phone']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('secondary_phone', $row['secondary_phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Avatar" class=" control-label col-md-4 text-left"> Avatar: </label>
                            <div class="col-md-6">
                                <input type='file' name='avatar' id='avatar' @if($row['avatar'] =='')
                                       @endif style='width:350px !important;' value="{{ $row['avatar'] }}"/>
                                @if(!empty($row['id']))
                                <div class="r-avatar">
                                    <br/>
                                    @if(file_exists(public_path()."/uploads/users/".$row['avatar']))
                                   <a href="/uploads/users/{{ $row['avatar'] }}" class="previewImage fancybox" rel="gallery1"> <img src="/uploads/users/{{ $row['avatar'] }}" style="box-shadow:1px 1px 5px gray" border="0" width="40px" class="img"/></a>
                                @else
                                        <img src="/silouette.png" style="box-shadow:1px 1px 5px gray" border="0" width="50px" class="img"/>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group  " >
                            <label for="street" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Street', (isset($fields['street']['language'])? $fields['street']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
					  <input type="text" name='street' id='street' class='form-control ' value="{{ $row['street'] }}">
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        {{--<div class="form-group  " >
                            <label for="City" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('City', (isset($fields['city']['language'])? $fields['city']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('city', $row['city'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>--}}
                        <div class="form-group  " >
                            <label for="State" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('State', (isset($fields['state']['language'])? $fields['state']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('state', $row['state'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Zip" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Zip', (isset($fields['zip']['language'])? $fields['zip']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('zip', $row['zip'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>

                        <div class="form-group  " >
                            <label for="Company Id" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Company', (isset($fields['company_id']['language'])? $fields['company_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='company_id' rows='5' id='company_id' class='select2 ' required  ></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Status" class=" control-label col-md-4 text-left"> Is Tech Contact: <span class="asterix">  </span></label>
                            <div class="col-md-6" style="padding-bottom: 15px;">

                                <label class='radio radio-inline'>
                                    <input type='radio' name='is_tech_contact' value='0' required
                                           @if($row['is_tech_contact'] == '0') checked="checked" @endif > No </label>
                                <label class='radio radio-inline'>
                                    <input type='radio' name='is_tech_contact' value='1'
                                           @if($row['is_tech_contact'] == '1') checked="checked" @endif > Yes </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Status" class=" control-label col-md-4 text-left"> Restricted Mgr Email: <span class="asterix">  </span></label>
                            <div class="col-md-6" style="padding-bottom: 15px;">

                                <label class='radio radio-inline'>
                                    <input type='radio' name='restricted_mgr_email' value='0' required
                                           @if($row['restricted_mgr_email'] == '0') checked="checked" @endif > No </label>
                                <label class='radio radio-inline'>
                                    <input type='radio' name='restricted_mgr_email' value='1'
                                           @if($row['restricted_mgr_email'] == '1') checked="checked" @endif > Yes </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Status" class=" control-label col-md-4 text-left"> Restricted User Email: <span class="asterix">  </span></label>
                            <div class="col-md-6" style="padding-bottom: 15px;">

                                <label class='radio radio-inline'>
                                    <input type='radio' name='restricted_user_email' value='0' required
                                           @if($row['restricted_user_email'] == '0') checked="checked" @endif > No </label>
                                <label class='radio radio-inline'>
                                    <input type='radio' name='restricted_user_email' value='1'
                                           @if($row['restricted_user_email'] == '1') checked="checked" @endif > Yes </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="Status" class=" control-label col-md-4 text-left"> Restrict Merch: <span class="asterix">  </span></label>
                            <div class="col-md-6" style="padding-bottom: 15px;">

                                <label class='radio radio-inline'>
                                    <input type='radio' name='restrict_merch' value='0' required
                                           @if($row['restrict_merch'] == '0') checked="checked" @endif > No </label>
                                <label class='radio radio-inline'>
                                    <input type='radio' name='restrict_merch' value='1'
                                           @if($row['restrict_merch'] == '1') checked="checked" @endif > Yes </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
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
                                   class=" control-label col-md-4"> {{ Lang::get('core.newpassword') }}: </label>
                            <div class="col-md-8">
                                <input name="password" type="password" id="password" class="form-control input-sm"
                                       value=""
                                       @if($row['id'] =='')
                                       required
                                        @endif
                                        {!! $errors->has('password')? "style='border-color: #cc0000;'":"" !!}
                                />
                                @if ($errors->has('password'))
                                    <span class="error_styles">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ipt"
                                   class=" control-label col-md-4"> {{ Lang::get('core.conewpassword') }}: </label>
                            <div class="col-md-8">
                                <input name="password_confirmation" type="password" id="password_confirmation"
                                       class="form-control input-sm" value=""
                                       @if($row['id'] =='')
                                       required
                                        @endif
                                        {!! $errors->has('password_confirmation')? "style='border-color: #cc0000;'":"" !!}
                                />
                                @if ($errors->has('password_confirmation'))
                                    <span class="error_styles">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  int-link">
                            <label for="ipt" class=" control-label col-md-4 text-right">Login Start Page: </label>
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
                                    @if($row['oauth_token'] && $row['refresh_token'])
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

                        <div class="col-sm-12 text-center btn-margin text-left-xs">
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
    </div>

    <style>
        #parsley-active{
            position: absolute;
            margin-top: 4px;
            margin-bottom: 4px;
            font-size: 13px;
        }
        #s2id_group_id{width: 100% !important;}
        #s2id_multiple_loc{width: 100% !important;}
        .error_styles strong{
            color: #cc0000;
            font-weight: normal;
        }
    </style>
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
        $("#company_id").jCombo("{{ URL::to('core/users/comboselect?filter=company:id:company_name_long') }}",
            {  selected_value : '{{ $row["company_id"] }}' });


        $('.editor').summernote();
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

        });

        var form = $('#user_form');

        form.submit(function(){
            App.functions.cleanupForm(form, {'email': ['trim'], 'email_2': ['trim']});
        });
    </script>
@stop
