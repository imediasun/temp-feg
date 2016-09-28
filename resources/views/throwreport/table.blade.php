<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5><i class="fa fa-table"></i></h5>
        <div class="sbox-tools">
            <!--
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search"
               onclick="reloadData('#{{ $pageModule }}','throwreport/data?search=')"><i class="fa fa-trash-o"></i> Clear
                Search </a>
            <a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data"
               onclick="reloadData('#{{ $pageModule }}','throwreport/data?return={{ $return }}')"><i
                        class="fa fa-refresh"></i></a>
            -->
            @if(Session::get('gid') ==1)
                <a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips"
                   title=" {{ Lang::get('core.btn_config') }}"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>
    <div class="sbox-content">

        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

        <?php echo Form::open(array('url' => 'throwreport/delete/', 'class' => 'form-horizontal', 'id' => 'SximoTable', 'data-parsley-validate' => ''));?>
        <div class="table-responsive">
            @if(!empty($topMessage))
                <h5 class="topMessage">{{ $topMessage }}</h5>
            @endif
            @if(count($rowData)>=1)
                <table class="table table-striped  " id="{{ $pageModule }}Table">
                    <thead>
                    <tr>
                        <th width="20"> No</th>

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
                    </tr>
                    </thead>

                    <tbody>
                    @if($access['is_add'] =='1' && $setting['inline']=='true')
                        <tr id="form-0">
                            <td> #</td>

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
                    <tr class="editable" id="{{ $row->id }}">
                        <td class="number"> <?php echo ++$i;?>  </td>
                        
                        @if($setting['view-method']=='expand')
                            <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}"
                                   data-url="{{ url('throwreport/show/'.$id) }}"><i class="fa fa-plus "></i></a></td>
                        @endif
                        <?php foreach ($tableGrid as $index => $field) :
                        if($field['view'] == '1') :
                        $conn = (isset($field['conn']) ? $field['conn'] : array());


                        $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn);
                        ?>
                        <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                @if($field['field']=='price_per_play')

                                    <input type="text" value="{{ number_format($value,2) }}" name="price_per_play"
                                           class="changed" id="price_per_play-{{ $row->id }}" style="width:55px"/>
                                @elseif($field['field']=='game_earnings')
                                    <span id="game_earnings-{{ $row->id }}">{{ $value }}</span>
                                @elseif($field['field']=='retail_price')
                                    <input type="text" value="{{ number_format($row->retail_price,2) }}"
                                           name="retail_price"
                                           class="changed" id="retail_price-{{ $row->id }}" style="width:55px"/>

                                @elseif($field['field']=='product_cogs_1')
                                    <span id="product_cogs_1-{{ $row->id }}">{{ number_format($row->retail_price * $row->product_throw_1,2) }}</span>

                                @elseif($field['field']=='game_throw')
                                    <span id="game_throw-{{ $row->id }}"><?php
                                        $cogs = $row->retail_price * $row->product_throw_1;
                                        if ($row->game_earnings != 0.00)
                                            echo number_format(($cogs / $row->game_earnings), 2);
                                        ?></span>

                                @elseif($field['field']=='product_throw_1')

                                    <input type="text" value="{{ $value }}" name="product_throw_1"
                                           class="changed" id="product_throw_1-{{ $row->id }}" style="width:55px"/>

                                @else {!! $value !!}
                                @endif
                            </td>
                        @endif
                        <?php
                        endif;
                        endforeach;
                        ?>
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
                    @if(!empty($message))
                        <p class='centralMessage'>{{ $message }}</p>
                    @else
                        <p class='centralMessage'> No Record Found </p>
                    @endif
                </div>

            @endif
            @if(!empty($bottomMessage))
                <h5 class="bottomMessage">{{ $bottomMessage }}</h5>
            @endif

        </div>
        <?php echo Form::close();?>
        <div class="table-footer">
            <div class="row">
                @if(!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false')
                    <div class="col-md-5 col-md-offset-7" id="<?php echo $pageModule;?>Paginate">
                        {!! $pagination->appends($pager)->render() !!}
                    </div>
                @endif
            </div>
        </div>
        </br>
        <div class="col-md-10 col-md-offset-3">
            <div class="col-md-10">
                @if(count($rowData) > 0)
                    <a id="report-submit-button" href="javascript:void(0);" class="btn btn-white"
                       style="font-size:1.4em; width:60%; text-align:center;">Submit Weekly Reports</a>
                @endif
            </div>
        </div>

        </br>
        </br>

    </div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script src="https://cdn.jsdelivr.net/momentjs/2.10.6/moment.min.js"></script>
<script>
    $(document).ready(function () {
        //Initialize the datePicker(I have taken format as mm-dd-yyyy, you can     //have your owh)
        //var lastWeekDate = new Date(new Date().setDate(new Date().getDate() - (new Date().getDay() == 0 ? 6 : 6)));
        var lastWeekDate = moment(new Date()).weekday(-1).format('MM/DD/YYYY');
        $(".weeklyDatePicker").datepicker({endDate: lastWeekDate, autoclose: true});
        $(".weeklyDatePicker").val('{{ $setDate }}');
        $('.weeklyDatePicker').datepicker().on('change', function (e) {
            var value = $(".weeklyDatePicker").val();
            var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
            var lastDate = moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
            selectedDate = firstDate + " - " + lastDate;
            $(".weeklyDatePicker").val(firstDate + " - " + lastDate);
            reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?search=date_start:equal:' + firstDate + '|date_end:equal:' + lastDate+'&config_id=' + $("#col-config").val());
            $('.weeklyDatePicker').datepicker("hide");
        });
        var defaultWeekValue = '{{$setDate}}';
        $('.weeklyDatePicker').datepicker().on('click blur hide', function (e) {
            $(".weeklyDatePicker").val(defaultWeekValue);
        });
        $('.tips').tooltip();
        $('#report-submit-button').on('click', (function () {
            $('.modal-title').html("Add Comment");
            var weekDate = $(".weeklyDatePicker").val();
            $('#sximo-modal-content').html('{!! Form::open(array("url"=>"throwreport/update-status/", "class"=>"form-horizontal","files" => true , "parsley-validate"=>"","novalidate"=>"","id"=> "throwreportFormAjax")) !!}'+
                            '<input type="hidden" name="weekdate" value="'+weekDate+'">'+
                            '<div class="form-group">'+
                            '<div class="col-md-12">'+
                            '<textarea name="comment" rows="5" class="form-control" placeholder="Comment" required></textarea>'+
                            '</div>'+
                            '</div>'+
                            '<div class="form-group" style="margin-bottom: 0px !important;">'+
                            '<div class="col-sm-12">'+
                            '<button style="float: right;" type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>'+
                            '</div>'+
                            '</div>'+
                            '{!! Form::close() !!}');
            $('#sximo-modal').modal('show');
            var form = $('#throwreportFormAjax');
            form.parsley();
            form.submit(function(){

                if(form.parsley('isValid') == true){
                    var options = {
                        dataType:      'json',
                        beforeSubmit :  showRequest,
                        success:       showResponse
                    }
                    $(this).ajaxSubmit(options);
                    return false;

                } else {
                    return false;
                }

            });

        }));
        $('.changed').on("change", (function (e) {
            /*
             game_throw => payout %
             product_cogs_1 => cost of good sold
             product_throw_1 => pc payout
             */
            var rowID = $(this).parents('tr').attr('id');
            var price_per_play = $(this).parents('tr').find("input[name='price_per_play']").val();
            var retail_price = $(this).parents('tr').find("input[name='retail_price']").val();
            var product_throw_1 = $(this).parents('tr').find("input[name='product_throw_1']").val();
            var product_cogs_1 = $(this).parents('tr').find("#product_cogs_1-" + rowID).text();
            var game_throw = $(this).parents('tr').find("#game_throw-" + rowID).text();
            var game_earnings = $(this).parents('tr').find("#game_earnings-" + rowID).text();

            product_cogs_1 = retail_price * product_throw_1;
            product_cogs_1 = product_cogs_1.toFixed(2);
            if (product_cogs_1 != '0.00') {
                game_throw = game_earnings / product_cogs_1;
                game_throw = game_throw.toFixed(2);
                $(this).parents('tr').find("#game_throw-" + rowID).text(game_throw);
            }
            $(this).parents('tr').find("#product_cogs_1-" + rowID).text(product_cogs_1);


            $.ajax(
                    {
                        url: "throwreport/temp",
                        type: 'post',
                        data: {
                            price_per_play: price_per_play,
                            retail_price: retail_price,
                            product_throw_1: product_throw_1,
                            product_cogs_1: product_cogs_1,
                            game_throw: game_throw,
                            id: rowID,
                        },
                        success: function (result) {

                        }

                    }
            );

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

        <?php if ($setting['view-method'] == 'expand') :
        echo AjaxHelpers::htmlExpandGrid();
    endif;
        ?>
    });
    function showRequest()
    {
        $('.ajaxLoading').show();
    }
    function showResponse(data)  {

        if(data.status == 'success')
        {
            $('.ajaxLoading').hide();
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            $('#sximo-modal').modal('show');
            return false;
        }
    }

    $("#col-config").on('change', function () {
        var value = $(".weeklyDatePicker").val();
        var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
        var lastDate = moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
        reloadData('#{{ $pageModule }}', '{{ $pageModule }}/data?config_id=' + $("#col-config").val()+ '&search=date_start:equal:' + firstDate + '|date_end:equal:' + lastDate);
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
