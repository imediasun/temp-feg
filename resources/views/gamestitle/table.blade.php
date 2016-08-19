<?php usort($tableGrid, "SiteHelpers::_sort");

?>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>
        <div class="sbox-tools">
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','gamestitle/data?search=')"><i class="fa fa-trash-o"></i> Clear
                Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','gamestitle/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            @if(Session::get('gid') ==1)
                <a href="{{ url('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">
        <?php  if(!isset($group_id)):
            $group_id=0;
        endif
        ?>
        @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])
        <?php echo Form::open(array('url' => 'gamestitle/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(count($rowData)>=1)
                <table class="table table-striped  " id="{{ $pageModule }}Table" style="table-layout: fixed;width:100%;">
                    <thead>
                    <tr>
                        <th width="20"> No</th>

                        <th width="60"><input type="checkbox" class="checkall"/></th>

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

                        <th width="370"><?php echo Lang::get('core.btn_action');?></th>


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

                        <td>{!! SiteHelpers::showUploadedFile($row->img,'/uploads/games/images/',50,false) !!}</td>





                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('location/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
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
                                {!! $value !!}
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>




                        <td data-values="action" data-key="<?php echo $row->id;?>">
                            {!! AjaxHelpers::buttonAction('gamestitle',$access,$id ,$setting) !!}
                            {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}







                            <a class="btn btn-warning btn-xs" href="{{ URL::to('gamestitle/upload/'.$row->id.'?type=2')}}"><i class="fa fa-picture-o" aria-hidden="true"></i></a>



                            <a class="btn btn-warning btn-xs" href="{{ URL::to('gamestitle/upload/'.$row->id.'?type=3')}}"><i class="fa fa-picture-o" aria-hidden="true"></i></a>


                            <a class="btn btn-warning btn-xs" href="{{ URL::to('gamestitle/upload/'.$row->id.'?type=1')}}"><i class="fa fa-picture-o" aria-hidden="true"></i></a>

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
        @include('ajaxfooter')

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
    $(document).ready(function () {
        $('.tips').tooltip();
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

        <?php if($setting['view-method'] =='expand') :
        echo AjaxHelpers::htmlExpandGrid();
    endif;
        ?>
    });

    $(".fancybox").fancybox({
        openEffect	: 'none',
        closeEffect	: 'none'
    });
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }


    .btn-imagee{

        font-size: 10px; padding: 7px 7px;border: 1px solid transparent;  border-radius: 0px;
        background-color: #428bca;
        border-color: #2a6496;		white-space: nowrap;
        font-family: 'Lato', sans-serif;}

</style>
