@extends('layouts.app')

@section('content')

    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Upload Image <small>Profile Image</small></h3>

            </div>
        </div>

        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> Upload Image <small>Profile Image</small></h4></div>
                <div class="sbox-content">
                    <div class="row" >
                        <div class="col-md-12" style="text-align: center;margin-bottom: 10px;">
                        <h1>Upload Image</h1>
                        <p style="color:red;">
                            THE IMAGE SHOWN WILL BE OVERWRITTEN <br/>
                            **MUST BE jpg, jpeg, gif, png**
                        </p>
                            </div>
                    <div class="col-md-6" >
                            {!! SiteHelpers::showUploadedFile($profile_img,'/uploads/users/',400,false) !!}
                    </div>
                    <div class="col-md-6">
                    {!! Form::open(array('url'=>'core/users/upload?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

                    <div class="form-group" style="margin-top:120px;">
                                <input  type='file' name='avatar' id='avatar'  required  style='width:150px !important;'     value=""  />
                        <input type="hidden" value="{{ Request::segment(4) }}" name="id"/>
                    </div>
                        <div class="form-group" style="margin-top:50px;">
                                <button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
                                <button type="button" onclick="location.href='{{ URL::to('core/users?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>

                    </div>

            </div>
                    {!! Form::close() !!}
                        <ul class="parsley-error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                </div>

            </div>
        </div>
    </div>

@stop