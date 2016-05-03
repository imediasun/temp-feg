@extends('layouts.app')

@section('content')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> {{ $pageTitle }}
                <small>{{ $pageNote }}</small>
            </h4>
        </div>
        <div class="sbox-content">

            <div class="col-md-6 col-md-offset-3 " style="border-bottom:1px solid lightgray">
                <h1>{{ $row->vendor_description }}</h1>

                <h2 class="text-center" > @if($row->inactive == 1) <span style="color:red"> {{ "NOT AVAILABLE" }} </span> @else <span style="color:green"> {{ "AVAILABLE" }} </span> @endif </h2>
            </div>
            <div class="col-md-3"></div>
            <div class="clearfix"></div>
            <div class="row">
                <br/><br/>


                <div class="col-md-5 col-md-offset-1" style="padding:35px;background:#FFF;box-shadow:1px 1px 5px lightgray" >

                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Item Description', (isset($fields['item_description']['language'])? $fields['item_description']['language'] : array())) }}
                            </td>
                            <td>{{ $row->vendor_description }} </td>

                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Add\'l Details', (isset($fields['details']['language'])? $fields['details']['language'] : array())) }}
                            </td>
                            <td>{{ $row->details }} </td>

                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Vendor ', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }}
                            </td>
                            <td>{!! SiteHelpers::gridDisplayView($row->vendor_id,'vendor_id','1:vendor:id:vendor_name')
                                !!}
                            </td>


                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Sku', (isset($fields['sku']['language'])? $fields['sku']['language'] : array())) }}
                            </td>
                            <td>{{ $row->sku }} </td>

                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Case Price', (isset($fields['case_price']['language'])? $fields['case_price']['language'] : array())) }}
                            </td>
                            <td>$ {{ $row->case_price }} </td>

                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('# Per Case', (isset($fields['num_items']['language'])? $fields['num_items']['language'] : array())) }}
                            </td>
                            <td>{{ $row->num_items }} </td>

                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Unit Price', (isset($fields['unit_price']['language'])? $fields['unit_price']['language'] : array())) }}
                            </td>
                            <td>$ {{ $row->unit_price }} </td>

                        </tr>
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {{ SiteHelpers::activeLang('Retail Price', (isset($fields['retail_price']['language'])? $fields['retail_price']['language'] : array())) }}
                            </td>
                            <td>$ {{ $row->retail_price }} </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-5" >
                    <?php
                    echo SiteHelpers::showUploadedFile($row->img, '/uploads/products/',400, false);
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
@endsection
