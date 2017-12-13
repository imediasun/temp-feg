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
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> {{ $pageTitle }} </h4></div>
                <div class="sbox-content">


                    <div class="row" >
                        
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                            <h1>Upload {{ $pageTitle }}</h1>
                            <p style="color:red;">
                                NOTE: THIS WILL OVERWRITE EXISTING {{ strtoupper($pageTitle) }}!<br/>
                                {{ $upload_inst }}
                            </p>
                            </div>
                        </div>

                        <div class="col-md-6 text-center gameImageContainer" >
                            {!! SiteHelpers::showUploadedFile($game_image,'/uploads/games/images/',false) !!}
                        </div>
                        
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                
                                <h2> {{ $game_title }}</h2>
                            </div>
                            
                            {!! Form::open(array('url'=>'gamestitle/upload?return='.$return, 'class'=>'form-horizontal col-sm-12','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

                            <div class="form-group" style="margin-top:120px;">
                                <input  type='file' name='avatar' id='avatar'  required  style='width:175px !important;'     value=""  />
                                <input type="hidden" value="{{ $game_id }}" name="id"/>
                                <input type="hidden" value="{{ $upload_type }}" name="upload_type">
                            </div>
                            
                            <div class="form-group" style="margin-top:50px;">

                                <button type="submit" name="submit" class="btn btn-primary btn-sm" style="margin-bottom:3px">
                                    <i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
                                    
                                <button style="margin-bottom:3px" type="button" onclick="location.href='{{ URL::to('gamestitle') }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                                <button style="margin-bottom:3px" type="button" onclick="deleteImage()" class="btn btn-danger btn-sm "><i class="fa  fa-cancel "></i>  {{ Lang::get('core.image_delete') }} </button>

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
    </div>
<script>
    function deleteImage() {
        if (confirm("Do you really want to delete existing image...")) {
            location.href="{{ url() }}/gamestitle/imageremove/{{ $game_id }}";
        }
    }
</script>
@stop
