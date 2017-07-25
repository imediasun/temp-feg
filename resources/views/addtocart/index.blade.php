@extends('layouts.app')

@section('content')
    <div class="page-content row">
        <!-- Begin Header & Breadcrumb -->
        <div class="page-header">
            <div class="page-title">
                <h3> <?php echo $pageTitle;?>
                    <small>{{ $pageNote }}</small>

                </h3>
            </div>
            <ul class="breadcrumb">
                <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ul>
        </div>
        <!-- End Header & Breadcrumb -->

        <!-- Begin Content -->
        <div class="page-content-wrapper m-t">
            <div class="resultData"></div>
            <div id="{{ $pageModule }}View"></div>

            <div id="{{ $pageModule }}Grid"></div>
        </div>
        <!-- End Content -->
    </div>
    <?php
    if(! isset($id)){
        $id= 0;
    }
    ?>
    <script>
        $(document).ready(function () {
            var id ={{$id}}
            if (id) {
                reloadData('#addtocart', './data');
            }
            else {
                reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data');
            }
        });

        function updateCart() {
            var ele=$("input[name^=qty]");
            ele.each(function(){
                var vendor=$(this).data('vendor');
                var id= $(this).attr('id');
                var qty= $(this).val();
                $('.ajaxLoading').show();
                doStuff(qty,id,vendor);
            });
        }

        function doStuff(value,id,vendor_name) {
            $.ajax({
                url:"addtocart/save/"+id+"/"+value+"/"+encodeURIComponent(vendor_name) ,
                method:'get',
                dataType:'json',
                success:function(data){
                    loadCart(vendor_name,data.subtotal);
                },
                error: function(){
                    unblockUI();
                },

            });
        }

        function loadCart(vendor_name,subtotal)
        {
            getCartData(false,vendor_name,subtotal);
            // return false;
        }

        function confirmSubmit(def) {
            var shortMessage;
            if(amt_short_msg == null) {
                shortMessage = def;
            }
            else
            {
                shortMessage=amt_short_msg;
            }
            shortMessage=shortMessage.replace(/&quot;/g, '');
            shortMessage=shortMessage.trim();
            if ( shortMessage && shortMessage.length > 0 ) {

                var text="Please increase order amount in order to proceed."
                alert(shortMessage +" "+ text);

            }
            else {
                if (confirm("Have you confirmed all items and quantities in your shopping cart?")) {
                    var new_location = '';
                    var checked = $("#clone_order").parent('[class*="icheckbox"]').hasClass("checked");

                    if (checked) {
                        if ($('#new_location').val() > 0) {
                            new_location = '/' + $('#new_location').val();
                            window.location.href = '{{ $pageModule }}/submit-requests' + new_location;
                        }
                        else {
                            alert("You must pick a location to clone this order to!");
                        }
                    }
                    else {
                        //   alert('{{ $pageModule }}/submit-requests');
                        var inputs = document.getElementsByClassName('cartProductsItems'),
                        products  = [].map.call(inputs, function( input ) {
                            return input.value;
                        }).join( '&products[]=' );

                        var redirectString = '{{ $pageModule }}/submit-requests?products[]='+products;
                        //alert(redirectString); return;
                        window.location.href = redirectString;
                        $("#update_text_to_add_cart").text('0');
                    }
                }

            }
        }

        var timer = null;

        function changeTotal(value,id,e)
        {
            var vendor_name1=$("#"+id).data('vendor');
            vendor_name1=vendor_name1.replace(/ /g, '_');
            if (e.keyCode == 13 && value > 0) {
                $('.ajaxLoading').show();
                e.preventDefault();
                doStuff(value,id,vendor_name1);
            }

        }

    </script>
@endsection