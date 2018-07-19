@extends('layouts.app')
@section('content')
    <style>
        .nav-tabs > li > a {
            margin-right: 0px !important;
        }
        .sbox {
            border-top: 0px solid transparent !important;
        }
    </style>
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
            <div id="merchindisetheminggallaryView"></div>
            <div id="redemptioncountergallaryView"></div>
            <div class="sbox">
                <div class="sbox-title">
                    <h5> <i class="fa fa-table"></i> </h5>
                    <div class="sbox-tools" >
                        <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
                        <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
                        @if(Session::get('gid') ==  \App\Models\Core\Groups::SUPPER_ADMIN)
                            <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                               title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
                        @endif
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <li style="width: 50%;" class="active" tab-data="" onclick="refreshTabContent();"><a class="moduleTab"
                                                                                                         href="#merchindisetheminggallary"
                                                                                                         data-toggle="tab" id="merchindisetheminggallary">Merchandise
                            Theme Gallery</a></li>
                    <li style="width: 50%;" onclick="refreshTabContent();"><a class="moduleTab" href="#redemptioncountergallary" data-toggle="tab"
                                                                              id="redemptioncountergallary">Redemption Counter Gallery</a></li>
                </ul>
            <div class="tab-content loadContent" id="merchindisetheminggallaryGrid"
                 style="background: #FFFFff; min-height:500px;"></div>
            <div class="tab-content loadContent" id="redemptioncountergallaryGrid"
                 style="background: #FFFFff; min-height:500px; display: none;"></div>
                </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <script>
        $(function () {
            loadModuleContent('merchindisetheminggallary');
            $(document).on('click', '.moduleTab', function () {
                var moduleTab = $(this);
                var loadContent = $("#loadContent");
                $("#merchindisetheminggallaryView").empty();
                $("#redemptioncountergallaryView").empty();
                $("#merchindisetheminggallaryGrid").empty();
                $("#redemptioncountergallaryGrid").empty();
                loadModuleContent(moduleTab.attr('id'));
            });
            $(document).on("keypress",".input-sm",function(e){
                if(e.which == 13 || e.keyCode == 13){
                    e.preventDefault();
                    console.log("Enter button prevented");
                    var container = $(this).parent(".sscol").parent('.simpleSearchContainer');
                    var buttonContainer = container.children(".sscol-submit");
                    buttonContainer.children('.doSimpleSearch').trigger("click");
                }
            })
        });
        $(document).on("click",".doSimpleSearch",function(){
            $("#merchindisetheminggallaryView").empty();
            $("#redemptioncountergallaryView").empty();
            $("#merchindisetheminggallaryGrid").empty();
            $("#redemptioncountergallaryGrid").empty();
        });
        function loadModuleContent(contentPath) {
            $('.ajaxLoading').show();
            $.ajax({
                url: '/' + contentPath + '/data',
                type: "POST",
                success: function (response) {
                    if (contentPath == "merchindisetheminggallary") {
                        $("#redemptioncountergallaryGrid").css("display", "none");
                        $("#redemptioncountergallaryGrid").empty();
                        $("#merchindisetheminggallaryGrid").css("display", "block");
                    } else {
                        $("#merchindisetheminggallaryGrid").css("display", "none");
                        $("#merchindisetheminggallaryGrid").empty();
                        $("#redemptioncountergallaryGrid").css("display", "block");
                    }
                    $("#" + contentPath + "Grid").html(response);
                    $('.ajaxLoading').hide();
                }
            });
        }
function refreshTabContent(){
    ajaxViewClose('#merchindisetheminggallary');
    ajaxViewClose('#redemptioncountergallary');
}
    </script>

@stop