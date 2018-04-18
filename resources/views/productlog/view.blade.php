@if($setting['view-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')">
                    <i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif

            <table class="table table-striped table-bordered">
                <tbody>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}
                    </td>
                    <td>{{ $row->id }} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Item Name', (isset($fields['vendor_description']['language'])? $fields['vendor_description']['language'] : array())) }}
                    </td>
                    <td>{{ $row->vendor_description }} </td>

                </tr>


                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Is Reserved', (isset($fields['is_reserved']['language'])? $fields['is_reserved']['language'] : array())) }}
                    </td>
                    <td>{!! $row->is_reserved == 0 ? 'No':'Yes' !!} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Reserved Qty', (isset($fields['reserved_qty']['language'])? $fields['reserved_qty']['language'] : array())) }}
                    </td>
                    <td>{{ $row->reserved_qty }} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Inactive', (isset($fields['inactive']['language'])? $fields['inactive']['language'] : array())) }}
                    </td>
                    <td>{!! $row->inactive == 0 ? 'No':'Yes' !!} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) }}
                    </td>
                    <td>{{ $row->updated_at }} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Allow Negative Reserved Qty', (isset($fields['allow_negative_reserve_qty']['language'])? $fields['allow_negative_reserve_qty']['language'] : array())) }}
                    </td>
                    <td>{!! $row->allow_negative_reserve_qty == 0 ? 'No':'Yes' !!} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Reserved Qty Par Amount', (isset($fields['reserved_qty_limit']['language'])? $fields['reserved_qty_limit']['language'] : array())) }}
                    </td>
                    <td>{{ !empty($row->reserved_qty_limit) ? $row->reserved_qty_limit :"No Data" }} </td>

                </tr>


                </tbody>
            </table>
            @if(!empty($productLogContent))
                <br />
                <div class="col-md-3 pull-right">
                    <div class="pull-right">
                        <a href="{{ URL::to( $pageModule .'/export/excel?id='.$id.'&return='.$return) }}"
                           class="btn btn-sm btn-white">
                            Excel</a>
                    </div>
                </div>
                <br />

                <?php
                $logContents = $productLogContent['Contents'];
                ?>
                <table class="table table-striped table-bordered" >
                    <tbody>
                    <tr>
                        <th colspan="7"><h4>Reserved Quantity Log</h4></th>
                    </tr>
                    <tr>
                        <th>Item ID</th>
                        <th>Order ID</th>
                        <th>Amount</th>
                        {{--<th>Added/<br>Reduced</th>--}}
                        <th>Reserved Quantity</th>
                        <th>Reason</th>
                        <th>Logged By</th>
                        <th>Logged At</th>
                    </tr>
                    @if(count($logContents)>0)
                        @foreach($logContents as $logContent)
                            <tr>
                                <td>{{ $logContent->product_id }}</td>
                                <td>{{ (!empty($logContent->order_id) || $logContent->order_id=0) ? $logContent->order_id:"No Data" }}</td>
                                <td>{{ $logContent->adjustment_type =='negative' ? ($logContent->adjustment_amount<0) ? $logContent->adjustment_amount:$logContent->adjustment_amount * -1:$logContent->adjustment_amount }}</td>
                                <td>{{$logContent->reservedQuantity}}</td>
                                {{--				<td>{{ !empty($logContent->adjustment_type=='negative') ? 'Reduced':'Added' }}</td>--}}
                                <td>{{ !empty($logContent->reserved_qty_reason) ? $logContent->reserved_qty_reason:"No Data" }}</td>
                                <td>{{ $logContent->adjusted_by }}</td>
                                <td>{{ $logContent->created_at }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" align="center">No existing record found.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            @endif
            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif

<script>
    $(document).ready(function(){

    });
</script>	f