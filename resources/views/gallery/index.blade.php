@extends('layouts.app')
@section('content')
    <style>
        .nav-tabs > li > a {
            margin-right: 0px !important;
        }
        .sbox {
            border-top: 0px solid transparent !important;
        }
        .sbox-content{ border-color: #f9f9f9; }
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
            <div class="sbox">
                <div class="sbox-title">
                    <h5> <i class="fa fa-table"></i> </h5>
                    <div class="sbox-tools">
                        <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="" id="clearSearchbtn" data-original-title="Clear Search"><i class="fa fa-trash-o"></i> Clear Search </a>
                        <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="" id="reloadBtb"  data-original-title="Reload Data"><i class="fa fa-refresh"></i></a>
                        <a href="#" class="btn btn-xs btn-white tips" id="settingbtn" title="" data-original-title=" Configuration"><i class="fa fa-cog"></i></a>
                    </div>
                </div>
            <div class="gallery-tabs-style">
                <span  class="active merchindisetheminggallarytab"  onclick="refreshTabContent(); $('.redemptioncountergallarytab').removeClass('active'); $(this).addClass('active')"><a class="moduleTab"
                                                                      href="#merchindisetheminggallary"
                                                                       id="merchindisetheminggallary">Merchandise
                        Theme Gallery</a></span>
                <span  onclick="refreshTabContent(); $('.merchindisetheminggallarytab').removeClass('active'); $(this).addClass('active')"
                     class="redemptioncountergallarytab not-first">
                    <a class="moduleTab" href="#redemptioncountergallary"
                                           id="redemptioncountergallary">Redemption Counter Gallery</a></span>
            </div>
            <div id="merchindisetheminggallaryView"></div>
            <div id="redemptioncountergallaryView"></div>
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
                return false;
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