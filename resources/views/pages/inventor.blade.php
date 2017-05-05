@extends('layouts.app') @section('content')
    <div class="page-content-wrapper m-t">
        <div class="sbox animated fadeInRight">
            <div class="sbox-title">&nbsp;{{ $pageTitle }}{!! $editLink !!}</div>
            <div class="sbox-content">
                <div class="col-md-12" style="padding-top: 50px; padding-right: 50px; padding-bottom: 50px; background-color: #ffffff;">
                <p><strong>Physical Inventory Instructions:</strong> <br /><a href="/tech/physical_inventory.doc" target="_blank"><span style="color: #808080;">How to Perform a Physical Inventory </span></a><br /><br /><strong>Asset Tag Placement Instructions:</strong> <br /><a href="/tech/asset_tag_placement.doc" target="_blank"><span style="color: #808080;">How to Apply Asset Tags</span> <span style="color: #000000;"><strong>**MUST READ BEFORE APPLYING TAGS**</strong></span> </a><br /><br /><strong>Instructions for Installing QR Code Scanner:</strong> <br /><span style="color: #808080;">Visit this page from your mobile phone and click on the link below that corresponds to your phone type. Follow the installation instructions.</span> <br /><a href="https://play.google.com/store/apps/details?id=me.scan.android.client" target="_blank"><span style="color: #808080;">Download QR Code Scanner for <strong>Android Mobile Phone</strong> </span></a><br /><a href="https://itunes.apple.com/us/app/scan/id411206394?mt=8" target="_blank"><span style="color: #808080;">Download QR Code Scanner for <strong>Apple iPhone</strong> </span></a><br /><span style="color: #808080;"><a style="color: #808080;" href="http://www.windowsphone.com/en-us/store/app/scan/c62d7a1c-c336-4394-84d0-2ea4913fc891" target="_blank">Download QR Code Scanner for <strong>Windows Phone</strong></a></span></p>
                </div>
                <div class="clearfix">&nbsp;</div>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
    </div>
    <div class="clearfix">&nbsp;</div>
@stop
