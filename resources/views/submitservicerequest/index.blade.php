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
                <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ul>

        </div>


        <div class="page-content-wrapper m-t">
            <div class="sbox animated fadeInRight">
                <div class="sbox-title"><h5><i class="fa fa-table"></i></h5>

                    <div class="sbox-tools">
                        @if(Session::get('gid') ==1)
                            <a href="{{ URL::to('feg/module/config/'.$pageModule) }}"
                               class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}"><i
                                        class="fa fa-cog"></i></a>
                        @endif
                    </div>
                </div>
                <div class="sbox-content">
                    <div class="col-md-6" style="background: #FFF;box-shadow:1px 1px 5px lightgray;padding:30px">
                        <h2 class="text-center">IT / Parts / Service Request</h2>
                        <hr/>
                        {!! Form::open(array('url'=>'submitservicerequest/save/',
                        'class'=>'form-horizontal' ,'id' =>'submitservicerequest' )) !!}
                        <div class="form-group  ">
                            <label for="location_id" class="control-label col-md-4 text-left">
                                For Location
                            </label>

                            <div class="col-md-8">
                                <select name="location_id" id="location_id" class="select2"></select>
                            </div>
                        </div>
                        <div class="form-group " id="game_div">
                            <label for="game_id" class="control-label col-md-4 text-left">
                                For Game :
                            </label>

                            <div class="col-md-8">
                                <select name="game_id" id="game_id" class="select2" onchange="grabGameId()"></select>
                            </div>
                        </div>
                        <div class="form-group ">

                            <label for="tech_type" class=" control-label col-md-4 text-left">
                                <b style="color:red">IT Service Request</b> </label>

                            <div class="col-md-8">
                                <input type="radio" name="tech_type" value="service" id="tech_type" checked="checked"/>
                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="requestTitle" class=" control-label col-md-4 text-left">
                                Title
                            </label>

                            <div class="col-md-8">
                                <input type="text" class="form-control"
                                       placeholder="Title" maxlength="35"
                                       value=""
                                       name="requestTitle" id="requestTitle">
                            </div>
                        </div>
                        <div class="form-group  " id="description_div">
                            <label for="description" class=" control-label col-md-10 text-left">
                                Detailed description parts request and explanation of game problem
                            </label>

                            <div class="col-md-4"></div>

                            <div class="col-md-8">
                                <br/>
                                <textarea class="form-control" name="description" id="description" cols="40"
                                          rows="6"></textarea>
                            </div>

                        </div>
                        <div class="form-group" id="cost_div">
                            <label for="cost" class=" control-label col-md-4 text-left">
                                Part Cost
                            </label>

                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Cost" value="" name="part_cost"
                                       id="cost">
                            </div>

                        </div>
                        <div class="form-group" id="qty_div">
                            <label for="qty" class=" control-label col-md-4 text-left">
                                Quantity Needed: </label>

                            <div class="col-md-8">
                                <input typ="text" name="qty" value="" class="form-control" id="qty"/>
                            </div>

                        </div>
                        <div class="form-group" id="date_needed_div">
                            <label for="date_needed" class="control-label col-md-4 text-left date">
                                Date Needed: </label>

                            <div class="col-md-8">
                                <input typ="text" class="form-control date" name="need_by_date" value="" id="date_needed"
                                        />
                            </div>

                        </div>
                        <div class="form-group" id="game_down_div">
                            <label for="game_down" class=" control-label col-md-4 text-left">
                                Game is Down: </label>

                            <div class="col-md-8">
                                <input type="checkbox" name="game_down" value="1" id="game_down"/>
                                <input type="hidden" name="game_down" value="0" id="game_down"/>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="userfile" class=" control-label col-md-4 text-left">
                                Upload File </label>

                            <div class="col-md-8">
                                <input type="file" name="userfile" class="" id="userfile"/>
                            </div>

                        </div>
                        <div class="form-group  ">
                            <input type="hidden" id="company_id" name="company_id"
                                   value="{{ \Session::get('company_id') }}">

                            <div class="form-group">
                                <label class="col-sm-4 text-right">&nbsp;</label>

                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-primary btn-sm "><i
                                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')"
                                            class="btn btn-success btn-sm"><i
                                                class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }}
                                    </button>
                                </div>
                            </div>


                            {!! Form::close() !!}
                        </div>
                        <hr/>
                        <h4 class="text-center">Submitted
                            by {{ \Session::get('first_name') }} {{ \Session::get('last_name') }}
                            on {{ date('m/d/Y') }}</h4>

                    </div>
                    <div class="col-md-6">
                        @if(!empty($data['game_details'][0]->game_title_id))

                            <div class="col-md-11 col-md-offset-1"
                                 style="background:#FFF;box-shadow: 1px 1px 5px lightgray;padding:20px;">
                                <div class="form-group ">
                                    <div class="col-md-12 ">
                                        <?php    echo SiteHelpers::showUploadedFile($data['game_details'][0]->game_title_id, '/uploads/games/', 350, false); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group " style="margin-top:20px">
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Manufacturer:</b>
                                    </label>

                                    <div class="col-md-8">
                                        {{ $data['game_details'][0]->vendor_name }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Phone:</b> </label>

                                    <div class="col-md-8">
                                        {{ $data['game_details'][0]->vendor_phone }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Contact:</b></label>

                                    <div class="col-md-8">
                                        {{ $data['game_details'][0]->vendor_contact }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Email:</b> </label>

                                    <div class="col-md-8">
                                        {{ $data['game_details'][0]->vendor_email  }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Website:</b> </label>

                                    <div class="col-md-8">
                                        @if(!empty($data['game_details'][0]->vendor_website))
                                            <a href="http://{{ $data['game_details'][0]->vendor_website }}"
                                               target="_blank" class="download"
                                               style="font-weight:bold;"> {{ $data['game_details'][0]->vendor_website }}</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Game Type:</b> </label>

                                    <div class="col-md-8">
                                        {{ $data['game_details'][0]->game_type }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Game Manual:</b> </label>

                                    <div class="col-md-8">
                                        @if ($data['game_details'][0]->has_manual == 1)
                                            <a href="{{ url() }}/uploads/games/manuals/ {{$data['game_details'][0]->manual}}"
                                               target="_blank"
                                               class="download" style="font-weight:bold;">Click to View Manual</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group ">
                                    <br/>
                                    <label for="userfile" class=" control-label col-md-4 text-left">
                                        <b>Service Bulletin:</b>
                                    </label>

                                    <div class="col-md-8">
                                        @if ($data['game_details'][0]->has_servicebulletin == 1)
                                            <a href="{{url()}}/uploads/games/bulletins/{{$data['game_details'][0]->game_title_id}}"
                                               target="_blank"
                                               class="download" style="font-weight:bold;">Click to View Bulletin</a>
                                        @endif
                                    </div>
                                </div>
                                @endif

                            </div>
                            <div class="clearfix"></div>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="ajaxLoading"></div>
    </div>



    <script>
        $(document).ready(function () {
                    $("#location_id").jCombo("{{ URL::to('shopfegrequeststore/comboselect?filter=location:id:id|location_name') }}",
                            {selected_value: '{{ \Session::get('selected_location') }}', initial_text: 'Select Location'});
                    var LID = <?php echo json_encode($data['LID']) ?>;
                    var GID = <?php echo json_encode($data['GID']) ?>;
                    if (LID) {
                        document.getElementById("location_id").value = LID;
                        $("#game_div").show();
                        $("#game_down_div").show();
                    }
                    else {
                        $("#game_div").hide();
                        // $("#description_div").hide();
                        $("#date_needed_div").hide();
                        $("#qty_div").hide();
                        $("#cost_div").hide();
                        $("#game_down_div").hide();
                    }
                    $("#game_div").hide();
                    // $("#description_div").hide();
                    $("#date_needed_div").hide();
                    $("#qty_div").hide();
                    $("#cost_div").hide();
                    $("#game_down_div").hide();
                    techChange('service');
                    var form = $('#submitservicerequest');
                    form.parsley();
                    form.submit(function(){

                        if(form.parsley('isValid') == true){
                            var options = {
                                dataType:      'json',
                                beforeSubmit :  showRequest,
                                success:       showResponse
                            }
                            $(this).ajaxSubmit(options);
                            return false;

                        } else {
                            return false;
                        }

                    });
                }
        );
        function showRequest()
        {
            $('.ajaxLoading').show();
        }
        function showResponse(data)  {

            if(data.status == 'success')
            {
                ajaxViewClose('#{{ $pageModule }}');
                //  ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
                notyMessage(data.message);$('.ajaxLoading').hide();

                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        $('#tech_type').on('ifChecked', function (event) {
            techChange('service');
        });
        function techChange(request) {

            if (request == "service") {
                $("#description_div").show();
                $("#date_needed_div").show();
                $("#qty_div").hide();
                $("#cost_div").hide();
                // document.getElementById("game_down").style.display = "inline";
                // document.getElementById("game_down_lbl").style.display = "inline";
            }
            else if (request == "part") {
                $("#description_div").show();
                $("#date_needed_div").show();
                $("#qty_div").show();
                $("#cost_div").show();
                // document.getElementById("game_down").style.display = "inline";
                // document.getElementById("game_down_lbl").style.display = "inline";
            }
        }
        function grabLocId() {
            var locID = $("#location_id").val();
            var firstLIDchar = locID.charAt(0);
            if (locID) {
                var url = document.URL;
                pos = url.search("/LID");
                oldLIDplus = url.substr(pos, 8);
                if (pos > 5) {
                    url = url.replace(oldLIDplus, "");
                }
                oldLID = oldLIDplus.substr(4, 4);
                if (locID !== oldLID) {
                    window.location = url + "/LID" + locID;
                }
            }
        }
        function grabGameId() {
            var gameID = $("#game_id").val();
            var firstGIDchar = gameID.charAt(0);
            if (gameID) {
                var url = document.URL;

                pos = url.search("/GID");
                oldGIDplus = url.substr(pos, 12);

                if (pos > 5) {
                    url = url.replace(oldGIDplus, "");
                }
                oldGID = oldGIDplus.substr(4, 8);
                if (gameID !== oldGID) {
                    window.location = url + "/GID" + gameID;
                }
            }
        }
    </script>
@stop