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
                <div class="sbox-title"> <h4> <i class="fa fa-table"></i> Upload Image <small>Profile Image</small>
                        <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#product')"><i class="fa fa fa-times"></i></a>
                    </h4>
                </div>
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
                            {!! SiteHelpers::showUploadedFile($img,'/uploads/products/',400,false) !!}
                    </div>
                    <div class="col-md-6">


                        {!! Form::open(array('url'=>'product/upload?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

                    <div class="form-group" style="margin-top:120px;">
                                <input  type='file' name='img' id='img'  required  style='width:150px !important;'     value=""  />
                        <input type="hidden" value="{{ Request::segment(3) }}" name="id"/>
                    </div>
                        <div class="form-group" style="margin-top:50px;">

                                <button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>

                            <button type="button" onclick="location.href='{{ URL::to('product?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>

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
            function ajaxViewClose()
            {
                location.href='{{ URL::to('product') }}';
            }
        </script>
@stop
