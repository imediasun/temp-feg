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
            <ul class="nav nav-tabs">
                <li style="width: 50%;" class="active" tab-data=""><a class="moduleTab"
                                                                      href="#merchindisetheminggallary"
                                                                      data-toggle="tab" id="merchindisetheminggallary">Merchandise
                        Theme Gallery</a></li>
                <li style="width: 50%;"><a class="moduleTab" href="#redemptioncountergallary" data-toggle="tab"
                                           id="redemptioncountergallary">Redemption Counter Gallery</a></li>
            </ul>
            <div id="merchindisetheminggallaryView"></div>
            <div id="redemptioncountergallaryView"></div>
            <div class="tab-content loadContent" id="merchindisetheminggallaryGrid"
                 style="background: #FFFFff; min-height:500px;"></div>
            <div class="tab-content loadContent" id="redemptioncountergallaryGrid"
                 style="background: #FFFFff; min-height:500px; display: none;"></div>
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
                loadModuleContent(moduleTab.attr('id'));
            });
        });
        function loadModuleContent(contentPath) {
            $('.ajaxLoading').show();
            $.ajax({
                url: '/' + contentPath + '/data',
                type: "POST",
                success: function (response) {
                    if (contentPath == "merchindisetheminggallary") {
                        $("#redemptioncountergallaryGrid").css("display", "none");
                        $("#merchindisetheminggallaryGrid").css("display", "block");
                    } else {
                        $("#merchindisetheminggallaryGrid").css("display", "none");
                        $("#redemptioncountergallaryGrid").css("display", "block");
                    }
                    $("#" + contentPath + "Grid").html(response);
                    $('.ajaxLoading').hide();
                }
            });
        }

    </script>

@stop