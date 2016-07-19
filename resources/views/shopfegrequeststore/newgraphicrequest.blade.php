@extends('layouts.app')
@section('content')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> {{ $pageTitle }}
                <small>{{ $pageNote }}</small>
            </h4>
        </div>
        <div class="sbox-content">
                {!! Form::open(array('url'=>'shopfegrequeststore/newgraphic',
                'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
                'newgraphicrequest')) !!}
            <div class="col-md-8 col-md-offset-2" style="background: #FFF;box-shadow:1px 1px 5px lightgray;padding:10px">
            <fieldset><legend> New Graphics Request </legend>
                <div class="form-group  " >
                    <label  for="game_info" class=" control-label col-md-4 text-left">
                        For Game [or game type]:
                    </label>
                    <div class="col-md-6">
                       <input type="text" name="game_info" id="game_info" class="form-control" required="required"/>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
               <div class="form-group  " >
                    <label for="graphics_description" class=" control-label col-md-4 text-left">
                       Detailed description of color, art, location and/or game colors, size requirements, etc.
                    </label>
                    <div class="col-md-6">
                        <textarea required="required" class="form-control "id="graphics_description" name="graphics_description" rows="8" cols="45"></textarea>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>   <div class="form-group  " >
                    <label for="qty" class=" control-label col-md-4 text-left">
                        Quantity
                    </label>
                    <div class="col-md-6">
                    <input type="text" id="qty" name="qty" value="" class="form-control" required="required"/>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>   <div class="form-group  " >
                    <label for="date_needed" class=" control-label col-md-4 text-left">
                        Date Needed
                    </label>
                    <div class="col-md-6">
                       <input type="text" class="date form-control" id="date_needed" name="date_needed" required="required"/>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>   <div class="form-group  " >
                    <label for="Id" class=" control-label col-md-4 text-left">
                       For Location:
                    </label>
                    <div class="col-md-6">
                    <select name="location_name" id="location_name" class="select2" required="required"></select>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="form-group  " >
                    <label for="Add Image" class=" control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Add Image', (isset($fields['add_image']['language'])? $fields['add_image']['language'] : array())) !!}
                    </label>
                    <div class="col-md-6">

                        <a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('add_image')"><i class="fa fa-plus"></i></a>

                        <div class="add_imageUpl">
                            <input  type='file' name='add_image'  />


                        </div>

                        <div class="col-md-2">

                        </div>
                    </div>
                </div> </fieldset>


                <div class="form-group" style="padding-left: 24px;margin-bottom:50px">
                        <label class="col-sm-4 text-centre">&nbsp;</label>

                        <button type="submit"  class="btn btn-primary btn-sm-5" style="padding-right: 20px;
                                padding-left: 20px"><i
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>

                    Submitted by <b>{{ \Session::get('fid') }}</b> on <b>{{ date('m/d/Y') }}</b>
                </div>



            </fieldset>
                </div>
            <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
    <div class="ajaxLoading"></div>
    <script>
        $("document").ready(function(){
          //  $('.ajaxLoading').show();
            $("#location_name").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=location:id:id|location_name') }}",
                    {selected_value: '', initial_text: 'Select Location'});
            var form = $('#newgraphicrequest');
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
        });
        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {

            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
                window.location="{{ url() }}/shopfegrequeststore";
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
    </script>
    <style>
        .ajaxLoading { background:#fff url( {{ url() }}/loading.gif) no-repeat center center; display:none; height:200px; position:absolute; width:100%; opacity: 0.5; left:0; top:0; height: 100%; z-index:9999;}
    </style>
@endsection
