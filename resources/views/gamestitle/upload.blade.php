@extends('layouts.app')

@section('content')
    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> Upload  <small>{{ $pageTitle }}</small></h3>

            </div>
        </div>

        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> {{ $pageTitle }} <small>{{ $pageNote}}</small></h4></div>
                <div class="sbox-content">


                    <div class="row" >
                        <div class="col-md-12" style="text-align: center;margin-bottom: 10px;">
                            <h1>Upload {{ $pageTitle }}</h1>
                            <p style="color:red;">
                                NOTE: THIS WILL OVERWRITE EXISTING {{ strtoupper($pageTitle) }}!<br/>
                                {{ $upload_inst }}
                            </p>
                        </div>

                        <div class="col-md-6" >
                            {!! SiteHelpers::showUploadedFile($game_image,'/uploads/games/images/',450,false) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12" style="margin-left:-30px;">
                                <h2> {{ $game_title }}</h2>
                                </div>
                            {!! Form::open(array('url'=>'gamestitle/upload?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

                            <div class="form-group" style="margin-top:120px;">
                                <input  type='file' name='avatar' id='avatar'  required  style='width:150px !important;'     value=""  />
                                <input type="hidden" value="{{ $game_id }}" name="id"/>
                                <input type="hidden" value="{{ $upload_type }}" name="upload_type">
                            </div>
                            <div class="form-group" style="margin-top:50px;">
                                <button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
                                <button type="button" onclick="location.href='{{ URL::to('gamestitle') }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>

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