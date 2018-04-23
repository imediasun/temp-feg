<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
@if(isset($cartData['subtotals']) && !empty($cartData['subtotals']))
<div class="sbox">
    <div class="sbox-title">
        <h5><a style="margin-left: 0 !important;" href="{{ url('shopfegrequeststore') }}" class="btn btn-xs btn-white tips" title="Shop FEG Request Store"><i class="fa fa-arrow-left"></i> Back to Shop Page</a>
        </h5>

        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','addtocart/data?search=')"><i class="fa fa-trash-o"></i>
                Clear Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','addtocart/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==  \App\Models\Core\Groups::SUPPER_ADMIN)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
        
    </div>
    
    <div class="sbox-content">
        @if($setting['usesimplesearch']!='false')
            <?php $simpleSearchForm = SiteHelpers::configureSimpleSearchForm($tableForm); ?>
            @if(!empty($simpleSearchForm))
                <div class="simpleSearchContainer clearfix">
                    @foreach ($simpleSearchForm as $t)
                        <div class="sscol {{ $t['widthClass'] }}" style="{{ $t['widthStyle'] }}">
                            {!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!}
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm) !!}
                        </div>
                    @endforeach
                    <div class="sscol-submit"><br/>
                        <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search </button>
                    </div>
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['cartData' => $cartData])

        <?php echo Form::open(array('url' => 'addtocart/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped  " id="{{ $pageModule }}Table">
                    <thead>
                    <tr>
                        <th width="30"> No</th>
                        @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                        <th width="50"><input type="checkbox" class="checkall"/></th>
                        @endif
                        <th width="100">Already on Order</th>
                        <th width="70"><?php echo Lang::get('core.btn_action');?></th>
                        <th width="100">Image</th>
                        @if($setting['view-method']=='expand')
                            <th></th> @endif
                        <?php foreach ($tableGrid as $t) :
                            if ($t['view'] == '1'):
                                $limited = isset($t['limited']) ? $t['limited'] : '';
                                if (SiteHelpers::filterColumn($limited)) {

                                    if($t['label'] !='No' && $t['label'] !='Image' && $t['label'] !='Already on Order'){
                                    echo '<th style=text-align:'.$t['align'].' width="' . $t['width'] . '">' . \SiteHelpers::activeLang($t['label'], (isset($t['language']) ? $t['language'] : array())) . '</th>';
                                    }
                                }
                            endif;
                        endforeach; ?>
                        <th width="100">Notes</th>


                    </tr>
                    </thead>

                    <tbody>
                    @if(($access['is_add'] =='1' || $access['is_edit']=='1' ) && $setting['inline']=='true' )
                        <tr id="form-0">
                            <td> #</td>
                            @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                            <td></td>
                            @endif
                            @if($setting['view-method']=='expand')
                                <td></td> @endif
                            @foreach ($tableGrid as $t)
                                @if($t['view'] =='1')
                                    <?php $limited = isset($t['limited']) ? $t['limited'] : ''; ?>
                                    @if(SiteHelpers::filterColumn($limited ))
                                        <td data-form="{{ $t['field'] }}"
                                            data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
                                            {!! SiteHelpers::transForm($t['field'] , $tableForm) !!}
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                            <td>
                                <button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i
                                            class="fa  fa-save"></i></button>
                            </td>
                        </tr>
                    @endif
                    <?php foreach ($rowData as $row) :
                    $id = $row->id;
                    ?>
                    <tr class="editable" id="form-{{ $row->id }}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') data-id="{{ $row->id }}" ondblclick="showFloatingCancelSave(this)" @endif>
                        <input type="hidden" class="cartProductsItems" value="{{$row->product_id}}"/>
                        <td class="number"> <?php echo ++$i;?>  </td>
                        @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
                        <td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id;?>" onkeypress="disableEnter(event)"/></td>
                        @endif

                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('addtocart/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
                        @endif
                        <td>
                            <span class="cart_already_ordered">{{$row->already_order_qty}}</span>
                        </td>
                        <td data-values="action" data-key="<?php echo $row->id;?>">
                            <div class=" action dropup"><a href="#" onclick="if(confirm('Are you sure you want to remove this item from cart?')){ return removeItemFromCart('{{ $row->id }}'); } return false; " class="btn btn-xs btn-white tips" title="" data-original-title="Remove"><i class="fa fa-trash-o"></i></a></div>
                        </td>
                        <td>
                            <?php
                            echo SiteHelpers::showUploadedFile($row->img, '/uploads/products/', 50, false, 0,false,'',false);
                            ?>
                        </td>
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());


                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn,isset($field['nodata'])?$field['nodata']:0);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>

                        @if(SiteHelpers::filterColumn($limited ))
                            @if($field['field'] !='' && $field['field'] !='img' && $field['field'] !='already_order_qty')
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">

                                @if($field['field']=='qty')
                                    <input type="number" value="{{ $value }}" min="1" step="1" id="{{ $row->id }}" data-vendor="{{ $row->vendor_name }}" style="width:55px"  class="inputqty qtyfield qtyfield_{{ $row->id }}"/>
                                    @else

                                    {!! $value !!}

                                @endif

                            </td>
                            @endif
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                        <td class="notes"><textarea id="{{ $row->id }}" data-vendor="{{ $row->vendor_name }}" class="notesfield notesfield_{{ $row->id }}"  name="notes" style="width: 100%;">{{ $row->notes }}</textarea></td>

                    </tr>
                    @if($setting['view-method']=='expand')
                        <tr style="display:none" class="expanded" id="row-{{ $row->id }}">
                            <td class="number"></td>
                            <td></td>
                            <td></td>
                            <td colspan="{{ $colspan}}" class="data"></td>
                            <td></td>
                        </tr>
                    @endif
                    <?php endforeach;?>

                    </tbody>

                </table>
                @if($setting['inline']!='false' && $setting['disablerowactions']=='false')
                    @foreach ($rowData as $row)
                        {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                    @endforeach
                @endif
            @else

                <div style="margin:100px 0; text-align:center;">

                    <p> No Record Found </p>
                </div>

            @endif

        </div>
        <?php echo Form::close();?>
        <!--  todo refactor code
            @if($cartData['shopping_cart_total'] >= 0)
                <br/>

                <div class="col-md-6 col-md-offset-2">
                    <div class="col-md-10" id="new_locationdiv">
                        <select name="new_location" id="new_location" class="select3"></select>
                    </div>
                </div>
               <div style=";margin-left:50px;" class="col-md-2">
                    <label>Clone Order Info</label>
                    <input type="checkbox" name="clone_order" id="clone_order"
                           style="height:25px; width:25px;vertical-align: middle">
                </div>
            @endif
        -->
            <div class="row" id="cart-footer-btns">

                <div class="col-md-offset-4 col-sm-offset-2 col-md-8 col-sm-10">
                    <div class="row">
               
                    <div class="col-md-10 col-sm-9 col-xs-12">
                    <input type="button" style="font-weight: bold;" class="btn btn-sm btn-success cartsubmitaction"
                           value="Submit Weekly Requests totalling {{\CurrencyHelpers::formatCurrency($cartData['shopping_cart_total'])}}"
                           onClick="confirmSubmit({{ json_encode($cartData['amt_short_message']) }});" id = "cartbtn"></button>
                    </div>
                </div>
                </div>
            </div>
        @include('ajaxfooter')



    </div>
</div>
@else
    <p style="color:white"> Location {{ \Session::get('selected_location') }}'s cart is empty..</p>
@endif
@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif

<script>
    $(".inputqty").on("keypress",function (e) {
        var keycode = e.which || e.charCode || e.keyCode;
        if (keycode == 8){
            return true;
        }
        return keycode >= 48 && keycode <= 57;
    });


    function disableEnter(e)
    {
        if (e.which == 13) {
            e.preventDefault();
        }
    }
    $(document).ready(function () {
        $('.tips').tooltip();
        <!-- todo refactor code
        $("#new_location").jCombo("{{ URL::to('order/comboselect?filter=location:id:id|location_name ') }}",
                {selected_value: ''});
        -->
        renderDropdown($(".select3 "), { width:"100%"});

        $('.my_form').on("keypress",(function(e) {
            console.log(e);
            if (e.which == 13) {

                var value = $(this).val();
                var id =$(this).parent().parent().attr("id");
                id = id.split("-");
                console.log(id[1]);
//                this.form.submit();

                $.ajax(
                        {url: "addtocart/save/"+id[1],
                            type: 'post',
                            data: {qty:value},



                    success: function(result){
//                    $("#div1").html(result);
                        location.reload();

                }

                        }
                );


            }
        }));

        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
        $('#{{ $pageModule }}Table .checkall').on('ifChecked', function () {
            $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
        });
        $('#{{ $pageModule }}Table .checkall').on('ifUnchecked', function () {
            $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
        });

        $('#{{ $pageModule }}Paginate .pagination li a').click(function () {
            var url = $(this).attr('href');
            reloadData('#{{ $pageModule }}', url);
            return false;
        });
        <!-- todo refactor code
        $('#new_locationdiv').hide();
        -->
        <?php if($setting['view-method'] =='expand') :
                echo AjaxHelpers::htmlExpandGrid();
            endif;
         ?>
        var simpleSearch = $('.simpleSearchContainer');
        if (simpleSearch.length) {
            initiateSearchFormFields(simpleSearch);
            simpleSearch.find('.doSimpleSearch').click(function(event){
                performSimpleSearch.call($(this), {
                    moduleID: '#{{ $pageModule }}',
                    url: "{{ $pageUrl }}",
                    event: event,
                    container: simpleSearch
                });
            });
        }

        initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}');
    });
    <!-- todo refactor code
    $('#clone_order').on('ifChecked', function () {
        $('#new_locationdiv').show();

    });
    $('#clone_order').on('ifUnchecked', function () {
        $('#new_locationdiv').hide();
    });
    -->
function removeItemFromCart(itemId){
    $('.ajaxLoading').show();
        $.ajax({
            type:"POST",
            data:{ids:itemId},
            url:'{{ Url('addtocart/delete') }}',
            success:function(data){
                if(data.status =='success')
                {
                    //console.log("called succes");
                    notyMessage(data.message);
                    reloadData('#addtocart','addtocart/data?return=');
                } else {
                    //console.log("called error");
                    notyMessageError(data.message);
                }
            }
        });
    return false;
}
$(function(){
    $(".qtyfield,.notesfield").on("keypress",function(){
        $(".cartsubmitaction").attr("disabled","disabled");
        $(".cartsubmitaction").removeClass("btn-success").addClass("btn-disable");
    });
    $(".qtyfield,.notesfield").on("focusout",function(){
        var qty= $(".qtyfield_"+id).val();
        qty = $.trim(qty);
        if(qty == '' || Number(qty) < 1){
            $(".cartsubmitaction").attr("disabled","disabled");
            $(".cartsubmitaction").removeClass("btn-success").addClass("btn-disable");
        }else{
            $(".cartsubmitaction").removeAttr("disabled");
            $(".cartsubmitaction").removeClass("btn-disable").addClass("btn-success");
        }

    });
    $(".qtyfield,.notesfield").on("change",function(){
        var qtyfield = $(this);
            var vendor=qtyfield.data('vendor');
            var id= qtyfield.attr('id');
            var qty= $(".qtyfield_"+id).val();
            var notes = $('.notesfield_'+id).val();
            $('.ajaxLoading').show();
        if(qty < 1) {
            $(".cartsubmitaction").attr("disabled","disabled");
            $(".cartsubmitaction").removeClass("btn-success").addClass("btn-disable");
            notyMessageError('Case Quantity can not be less than 1.');

            $('.ajaxLoading').hide();
        }else{
            $.ajax({
                url: "addtocart/save/" + id + "/" + qty + "/" + encodeURIComponent(vendor) + "/" + notes,
                method: 'get',
                dataType: 'json',
                success: function (data) {
                    reloadData('#addtocart', 'addtocart/data?return=');
                    $(".cartsubmitaction").removeAttr("disabled");
                    $(".cartsubmitaction").removeClass("btn-disable").addClass("btn-success");
                },
                error: function () {
                    unblockUI();
                },
            });
        }
    });
});
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

    /*Edit Buttons Code*/
    table .btn.btn-xs {
        height: 25px;
        line-height: 19px;
        margin: 0px !important;
        text-align: center;
    }

    table .btn.btn-xs {
        float: inherit;
        width: 25px;
    }

</style>
