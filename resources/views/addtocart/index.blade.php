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
    </script>
@endsection