<div class="row c-margin">
        <?php
      //  $cartData=\Session::get('cartData');
        if($cartData['subtotals'])
            {
        ?>

    <div class="col-md-8 col-md-offset-2" style="background: #FFF;box-shadow: 1px 1px 5px lightgray;padding:20px;margin-bottom:12px">

        <h1 class="text-center"><img src='{{ url() }}/sximo/images/shopping_cart.png'  /> {{ $cartData['title_2'] }}</h1>
        <div class="table-responsive" style="max-height: 800px;min-height: 100px;">
            <table class="table table-hover " id="cart_data_table">

                </table>
        </div>
    </div>
    <?php } ?>
            <div class="col-md-8">

                @if($access['is_remove'] ==1)
                    <a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
                @endif
                <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>

            </div>
            <div class="col-md-4 ">

            </div>
</div>
<script>
    var amt_short_msg=null;
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    $(document).ready(function(){
        var isOnLoad=true;
        getCartData(isOnLoad);

    });

    function getCartData(isOnLoad,vendor,subtotal)
    {
        vendor = vendor || "no";
        subtotal=subtotal || "no";

        var t = document.getElementById('cart_data_table');
        //$("#cart_data_table tr").remove();
        $.ajax({
            url:'addtocart/cartdata',
            method:'get',
            success:function(data){

                var row,cell1,cell2,vendor_name,vendor_total,total_row,total_cell1,total_cell2;
                if(isOnLoad) {
                    for (var i = 0; i < data['subtotals'].length; i++) {
                        row = t.insertRow(i);
                        cell1 = row.insertCell(0);
                        cell2 = row.insertCell(1);
                        cell2.id = data['subtotals'][i].vendor_name.replace(/[^A-Z0-9]/ig, "_").substr(0,6);
                        cell1.style = "padding:7px";
                        cell2.style = "padding:7px";
                        vendor_name = data['subtotals'][i].vendor_name;
                        vendor_total = " $ " + parseFloat(data['subtotals'][i].vendor_total).toFixed(2);
                        if (data['subtotals'][i].vendor_min_order_amt > 0) {
                            vendor_name = vendor_name + "( $" + data['subtotals'][i].vendor_min_order_amt + " Minimum )";
                        }

// Add some text to the new cells:
                        cell1.innerHTML = vendor_name;
                        cell2.innerHTML = vendor_total;
                    }
                    total_row = t.insertRow(data['subtotals'].length);
                    total_row.style="border-top:3px solid black";
                    total_cell1 = total_row.insertCell(0);
                    total_cell2 = total_row.insertCell(1);
                    total_cell2.id="total_amount";



// Add some text to the new cells:
                    total_cell1.innerHTML = "Total";
                    total_cell2.innerHTML =" $ "+  data['shopping_cart_total'];
                }
                else
                {
                    vendor=vendor.replace(/[^A-Z0-9]/ig, "_").substr(0,6);
                    $("#"+vendor).text(" $ "+subtotal);
                    $("#total_amount").text(" $ "+ data['shopping_cart_total']);
                    amt_short_msg=data['amt_short_message'];
                    $("#cartbtn").val(" Submit Weekly Requests totalling $ "+ data['shopping_cart_total']);

                }
                $('.ajaxLoading').hide();

            }
        });
    }

</script>
