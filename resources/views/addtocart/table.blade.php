<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
@if(isset($cartData['subtotals']) && !empty($cartData['subtotals']))
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i>
            <a href="{{ url('shopfegrequeststore') }}" class="btn btn-xs btn-white tips" title="Shop FEG Request Store"><i class="fa fa-arrow-left"></i>
                 Back to Shop Page</a></h5>

        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','addtocart/data?search=')"><i class="fa fa-trash-o"></i>
                Clear Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','addtocart/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==1)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">

        @include( $pageModule.'/toolbar',['cartData' => $cartData])

        <?php echo Form::open(array('url' => 'addtocart/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped  " id="{{ $pageModule }}Table">
                    <thead>
                    <tr>
                        <th width="30"> No</th>
                        <th width="50"><input type="checkbox" class="checkall"/></th>
                        <th width="100">Image</th>
                        @if($setting['view-method']=='expand')
                            <th></th> @endif
                        <?php foreach ($tableGrid as $t) :
                            if ($t['view'] == '1'):
                                $limited = isset($t['limited']) ? $t['limited'] : '';
                                if (SiteHelpers::filterColumn($limited)) {
                                    echo '<th align="' . $t['align'] . '" width="' . $t['width'] . '">' . \SiteHelpers::activeLang($t['label'], (isset($t['language']) ? $t['language'] : array())) . '</th>';

                                }
                            endif;
                        endforeach; ?>
                        <th width="100">Vendor</th>
                        <th width="100">Part Number</th>
                        <th width="100">Size</th>
                        <th width="100">Ticket Value</th>
                        <th width="100">Case Price</th>
                        <th width="100">Retails Price</th>
                        <th width="100">Details</th>
                        <th width="70"><?php echo Lang::get('core.btn_action');?></th>

                    </tr>
                    </thead>

                    <tbody>
                    @if($access['is_add'] =='1' && $setting['inline']=='true')
                        <tr id="form-0">
                            <td> #</td>
                            <td></td>
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
                    <tr class="editable" id="form-{{ $row->id }}">
                        <td class="number"> <?php echo ++$i;?>  </td>
                        <td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id;?>"/></td>
                        <td> <?php
                            echo SiteHelpers::showUploadedFile($row->img, '/uploads/products/', 50, false);
                            ?></td>
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('addtocart/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
                        @endif
                        <?php foreach ($tableGrid as $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());


                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                  @if($field['field']=='qty')
                                    {!! Form::text('qty', $value,array('class'=>'my_form', 'method'=>'post','style'=>'width:55px')) !!}
                                    @else
                                {!! $value !!}
                                @endif
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                        <td>{{ $row->vendor_name }}</td>
                        <td>{{ $row->sku }}</td>
                        <td>{{ $row->size }}</td>
                        <td>{{ $row->ticket_value }}</td>
                        <td>{{ $row->case_price }}</td>
                        <td>{{ $row->retail_price }}</td>
                        <td>{{ $row->notes }}</td>
                        <td data-values="action" data-key="<?php echo $row->id;?>">
                            {!! AjaxHelpers::buttonAction('addtocart',$access,$id ,$setting) !!}
                            {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                        </td>
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

            @else

                <div style="margin:100px 0; text-align:center;">

                    <p> No Record Found </p>
                </div>

            @endif

        </div>
        <?php echo Form::close();?>
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
            <div class="col-md-10 col-md-offset-2">
                <br/>

                <div class="col-md-10">
                    <input type="button" style="font-size:1.4em; width:100%; text-align:center;"
                           value="Submit Weekly Requests totalling ${{ $cartData['shopping_cart_total']}}"
                           onClick="confirmSubmit();"></button>
                </div>
            </div>
        @endif
        @include('ajaxfooter')

    </div>
</div>
@else
    <p style="color:red">Sorry! Location {{ \Session::get('selected_location') }}'s cart is empty..</p>
@endif
@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif

<script>
    $(document).ready(function () {
        $('.tips').tooltip();
        $("#new_location").jCombo("{{ URL::to('order/comboselect?filter=location:id:id|location_name ') }}",
                {selected_value: ''});
        $(".select3").select2({width: "98%"});

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
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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
        $('#new_locationdiv').hide();
        <?php if($setting['view-method'] =='expand') :
                echo AjaxHelpers::htmlExpandGrid();
            endif;
         ?>
    });
    $('#clone_order').on('ifChecked', function () {
        $('#new_locationdiv').show();

    });
    $('#clone_order').on('ifUnchecked', function () {
        $('#new_locationdiv').hide();
    });
    function confirmSubmit() {
        var shortMessage = "{{ json_encode($cartData['amt_short_message']) }}";
            shortMessage=shortMessage.replace(/&quot;/g, '');
            shortMessage=shortMessage.trim();
        if ( shortMessage && shortMessage.length > 0 ) {

            var text="Please increase order amount in order to proceed."
            alert(shortMessage + text);

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
                    window.location.href = '{{ $pageModule }}/submit-requests';
                }
            }

        }
    }

</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
