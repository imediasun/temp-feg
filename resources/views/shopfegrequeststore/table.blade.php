<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>

        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','shopfegrequeststore/data?search=')"><i
                        class="fa fa-trash-o"></i> Clear Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','shopfegrequeststore/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==1)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">

        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id),'order_type'=>$order_type,'product_type' => $product_type,'cart'=>$cart])

        <?php echo Form::open(array('url' => 'shopfegrequeststore/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped  " id="{{ $pageModule }}Table">
                    <thead>
                    <tr>
                        <th width="50"> No</th>
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

                        <th width="50">{{ Lang::get('core.btn_action') }}</th>
                        <th width="150">Add To Cart</th>





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
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('shopfegrequeststore/show/'.$id) }}"><i class="fa fa-plus "></i></a>
                            </td>
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
                                @if($field['field']=='img')
                                    <?php
                                    echo SiteHelpers::showUploadedFile($value, '/uploads/products/', 50, false);
                                    ?>
                                @else
                                    {!! $value !!}
                                @endif
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
                        <td><a href="{{ $pageModule }}/show/{{$row->id}}" target="_blank"
                               class="tips btn btn-xs btn-white"  title="Product Details"><i class="fa fa-search" aria-hidden="true"></i></a>
</td>
                        <td>@if($row->inactive == 0)
                                <input type="number" title="Quantity" onkeyup="if(!this.checkValidity()){this.value='';alert('Please enter a number')};" name="item_quantity" class="form-control" style="width:70px;display:inline" id="item_quantity_{{$row->id}}"  />
                                <a href="javascript:void(0)" value="{{$row->id}}" class=" addToCart tips btn btn-xs btn-white"  title="Add to Cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>

                            @else
                                Not Avail.
                            @endif</td>
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
        @include('ajaxfooter')

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
    $(document).ready(function () {
        $('.tips').tooltip();
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
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

        $('.addToCart').on('click',function(){
            var base_url = <?php echo  json_encode(url()) ?>;
            var addId = $(this).attr('value');
            var qty=$("#item_quantity_"+addId).val();

            if(!qty)
            {
                qty=0;
            }
            console.log(addId+ " "+qty);
            $.ajax({
                type: "GET",
                url: base_url + '/shopfegrequeststore/popup-cart/'+addId+"/"+qty,
                data: {
                },
                success: function (response) {
                    $("#update_text_to_add_cart").text(response.total_cart);
                    showResponse(response)
                }
            });
        });
        function showResponse(data)  {

            if(data.status == 'success')
            {
                notyMessage(data.message);

            } else {
                notyMessageError(data.message);
                return false;
            }
        }
    <?php if($setting['view-method'] =='expand') :
                echo AjaxHelpers::htmlExpandGrid();
            endif;
         ?>
    });
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
