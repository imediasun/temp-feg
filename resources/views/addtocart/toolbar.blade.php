<div class="row m-b">
        <?php
      //  $cartData=\Session::get('cartData');
        if($cartData['subtotals'])
            {
        ?>

    <div class="col-md-8 col-md-offset-2" style="background: #FFF;box-shadow: 1px 1px 5px lightgray;padding:20px;margin-bottom:12px">

        <h1 class="text-center"><img src='{{ url() }}/sximo/images/shopping_cart.png'  /> {{ $cartData['title_2'] }}</h1>
        <div class="table-responsive" style="">
            <table class="table table-hover">
                @foreach($cartData['subtotals'] as $data)
                    @if(isset($data['vendor_name']))
                <tr>
                 <td style="padding:7px">{{ $data['vendor_name'] }} @if($data['vendor_min_order_amt'] > 0) ( $ {{ $data['vendor_min_order_amt'] }} Minimum ) @endif</td>
                    <td style="padding:7px"> {{ $data['vendor_total'] }} </td>
                </tr>
                    @endif
                    @endforeach
                <tr style="border-top:3px solid #000"><td style="padding:7px">Total</td><td style="padding:7px">$ {{ $cartData['shopping_cart_total'] }}</td></tr>
            </table>
        </div>
    </div>
    <?php } ?>
            <div class="col-md-8">

                @if($access['is_remove'] ==1)
                    <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
                @endif
                <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>

            </div>
            <div class="col-md-4 ">

            </div>
</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
</script>