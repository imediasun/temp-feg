<?php
use App\Models\Order;
usort($tableGrid, "SiteHelpers::_sort");


?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips orderTableClearSearch" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','order/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips orderTableReload" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','order/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
			<a href="{{ url('feg/module/config/'.$pageModule) }}"
               class="btn btn-xs btn-white tips openModuleConfig"
               title=" {{ Lang::get('core.btn_config') }}"
               ><i class="fa fa-cog"></i></a>
            <a href="{{ url('feg/module/special-permissions/'.$pageModule.'/solo') }}"
                   class="btn btn-xs btn-white tips openSpecialPermissions" title="Special Permissions"
                ><i class="fa fa-sliders"></i></a>
			@endif
		</div>
	</div>
	<div class="sbox-content" style="padding-bottom:0">
        <?php
         $searched=\Request::get('search');
         $searched=explode("|",$searched);
        $searchedParams=[];
        foreach($searched as $t)
            {
                $searchedParams[]=explode(':',$t);
            }
        ?>
        @if($setting['usesimplesearch']!='false')
            <?php $simpleSearchForm = SiteHelpers::configureSimpleSearchForm($tableForm); ?>
            @if(!empty($simpleSearchForm))
                <div class="simpleSearchContainer clearfix">
                    @foreach ($simpleSearchForm as $t)
                        <div class="sscol {{ $t['widthClass'] }}" style="{{ $t['widthStyle'] }}">
                            <?php
                            $fv="";
                            foreach($searchedParams as $f)
                            {
                               $fv=in_array($t['field'],$f)?$f[2] :"";
                                if($fv != "")
                                    {
                                        break;
                                    }
                            }
                                if($t['field'] == "order_type_id")
                                {
                                    $fv=\Session::get('order_selected');
                                }
                            ?>
                            {!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!}
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm,false,$fv) !!}
                        </div>
                    @endforeach
                    {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
                </div>
            @endif
        @endif
        @include( $pageModule.'/toolbar',['colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])
			<div class="sbox-content" style="border: medium none; padding-top: 15px;">
            @if ($access['is_remove'] ==1 || !empty($pass['Can remove order']))
                <?php echo Form::open(array('url'=>'order/removeorderexplaination/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
            @endif
<div class="table-responsive">
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" style="position:relative">
        <thead>
        <tr class="row-">
            @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                <th width="35"> No </th>
            @endif
				@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1' || !empty($pass['Can remove order'])))
                <th width="30"> <input type="checkbox" class="checkall" /></th>
            @endif
            @if($setting['view-method']=='expand') <th>  </th> @endif
            <?php foreach ($tableGrid as $t) :
                if($t['view'] =='1'):
                    $limited = isset($t['limited']) ? $t['limited'] :'';
                    if(SiteHelpers::filterColumn($limited ))
                    {
                        $sortBy = $param['sort'];
                        $orderBy = strtolower($param['order']);
                        $colField = $t['field'];
                        $colIsSortable = $t['sortable'] == '1';
                        $colIsSorted = $colIsSortable && $colField == $sortBy;
                        $colClass = $colIsSortable ? ' dgcsortable' : '';
                        $colClass .= $colIsSorted ? " dgcsorted dgcorder$orderBy" : '';
                        $th = '<th'.
                                ' class="'.$colClass.'"'.
                                ' data-field="'.$colField.'"'.
                                ' data-sortable="'.$colIsSortable.'"'.
                                ' data-sorted="'.($colIsSorted?1:0).'"'.
                                ' data-sortedOrder="'.($colIsSorted?$orderBy:'').'"'.
                                ' style=text-align:'.$t['align'].
                                ' width="'.$t['width'].'"';
                        $th .= '>';
                        $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                        $th .= '</th>';
                        echo $th;
                    }
                endif;
            endforeach; ?>
            @if($setting['disablerowactions']=='false')
                <th width="250"><?php echo Lang::get('core.btn_action') ;?></th>
            @endif
        </tr>
        </thead>
        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0">
				<td class="cell"> # </td>
				@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1' || !empty($pass['Can remove order'])))
					<td class="cell"> </td>
				@endif
				@if($setting['view-method']=='expand') <td> </td> @endif
				@foreach ($tableGrid as $t)
					@if(isset($t['inline']) && $t['inline'] =='1')
					<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
						<td class="cell" data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
							{!! SiteHelpers::transInlineForm($t['field'] , $tableForm) !!}
						</td>
						@endif
					@endif
				@endforeach
				<td class="cell">
					<button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
				</td>
			  </tr>
			  @endif

           		<?php foreach ($rowData as $row) :
                    $id = $row->id;
                    $eid = \SiteHelpers::encryptID($id);
           		?>


                <tr  @if(empty($row->deleted_at)) class="editable" @endif data-id="{{ $row->id }}" id="form-{{ $row->id }}" @if(($setting['inline']!='false' && $setting['disablerowactions']=='false') || empty($row->deleted_at))  ondblclick="showFloatingCancelSave(this)" @endif>

					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
						<td class="number"> <?php echo ++$i;?>  </td>
					@endif
						@if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1' || !empty($pass['Can remove order'])))
						<td><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->po_number ;?>" />  </td>
					@endif


					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('order/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') :
							$conn = (isset($field['conn']) ? $field['conn'] : array() );
							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,isset($field['nodata'])?$field['nodata']:0);
                            if($row->$field['field'] == '0000-00-00')
                            {
                                $value = "No Data";
                            }
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                            @if($field['field']=='status_id' && !empty($row->deleted_at))

                                    {!! "Removed" !!}

                                @else
                                @if($field['field']=='notes' && !empty($row->notes))
							                <?php echo ltrim($value,'<br>'); ?>
                                    @else
                                             {!! $value !!}
                                    @endif
                                @endif
							 </td>
							@endif
						 <?php endif;
						endforeach;
					  ?>


                    <td data-values="action" data-key="<?php echo $row->id ;?>">

                        @if(empty($row->deleted_at))
                        {!! AjaxHelpers::GamestitleButtonAction('order',$access,$id ,$setting) !!}
                        <a href="{{ URL::to('order/po/'.$row->id)}}"
                            data-id="{{$eid}}"
                            data-action="po"
                           class="tips btn btn-xs btn-white orderGenPOAction"
                           title="Generate PO">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                        </a>
                        <a href="{{ $pageModule }}/update/{{$row->id}}/clone"
                            onclick="ajaxViewDetail('#order',this.href); return false; "
                            data-id="{{$eid}}"
                            data-action="clone"
                           class="tips btn btn-xs btn-white orderCloneAction"
                           title="Clone Order">
                            <i class=" fa fa-random" aria-hidden="true"></i>
                        </a>
                        <?php
                        $canPostToNetSuit = Order::canPostToNetSuit($row->id, $row);
                        $isApified = Order::isApified($id, $row);
                        ?>

                        @if(!$isApified)
                            <a href="{{ URL::to('order/orderreceipt/'.$row->id)}}"
                               data-id="{{$eid}}"
                               data-action="receipt"
                               class="tips btn btn-xs btn-white orderReceiptAction"
                               title="Receive Order">
                                <i class="fa fa fa-truck" aria-hidden="true"></i>
                            </a>
                        @endif

                        @if($row->status_id=='Open' || $row->status_id=='Open (Partial)')

                            <a href="{{ URL::to('order/removalrequest/'.$row->po_number)}}"
                               data-id="{{$eid}}"
                               data-action="removal"
                               class="tips btn btn-xs btn-white orderRemovalRequestAction"
                               title="Request Removal">
                                <i class="fa fa-trash-o " aria-hidden="true"></i>
                            </a>

                        @endif

                        @if($canPostToNetSuit  && !$isApified && Order::isApiable($id, $row, true))
                            <a href="javascript:void(0)"
                               data-id="{{$eid}}"
                               data-action="post"
                               class="tips btn btn-xs btn-white postToNetSuitAction"
                               title="{{ Lang::get('core.order_api_expose_button_label') }}">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            </a>
                        @endif

                        @if($row->invoice_verified == 0)
                            <a href="javascript:void(0)"
                               data-id="{{$eid}}"
                               data-action="post"
                               class="tips btn btn-xs btn-white verifyInvoiceAction"
                               title="{{ Lang::get('core.order_invoice_verify_btn_title') }}">
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            </a>
                        @endif
                            @else
                            <a href="{{ URL::to('order/restoreorder/'.$row->id)}}"
                               data-id="{{$eid}}"
                               data-action="removal"
                               class="tips btn btn-xs btn-white orderRemovalRequestAction"
                               title="Restore Order">
                                <i class="fa fa-refresh " aria-hidden="true"></i>
                            </a>
                        @endif
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
            @if ($access['is_remove'] == 1 || !empty($pass['Can remove order']))
                <?php echo Form::close() ;?>
            @endif
	@include('ajaxfooter')

	</div>
            @if($setting['inline']!='false' && $setting['disablerowactions']=='false')
                @foreach ($rowData as $row)
                    {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                @endforeach
            @endif
</div>
</div>
	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif

<script>
    $(document).ready(function() {
        $( document ).ajaxComplete(function( event, xhr, settings ) {
            console.log(xhr);
            console.log(settings);
            var $urlArray = settings.url.split('/');
            if(typeof($urlArray[2]) != "undefined" && $urlArray[2] !== null)
            {
                if ( settings.url === "order/save/"+$urlArray[2] ) {
                    var data = JSON.parse(xhr.responseText);
                    var selector = 'tr[data-id='+$urlArray[2]+'] td[data-field="order_total"]';
                    console.log(selector);
                    $(selector).attr('data-format','$ '+data.total).attr('data-values', data.total).text('$ '+data.total);
                }else if((settings.url).indexOf('order/data') !==-1){

                   <?php if($set_removed =="set_removed") { ?>
                    $("select[name='status_id'] option[value='removed']").attr('selected','selected');
                    $("select[name='status_id']").change();
                    <?php } ?>
            }
            }
        });
    $('.tips').tooltip();
        $('select[name="status_id"] option:first-child').text('All');
        $('select[name="status_id"]').change();
        $('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
	});
	$('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
	});
	$('#{{ $pageModule }}Table .checkall').on('ifUnchecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
	});

	$('#{{ $pageModule }}Paginate .pagination li a').click(function() {
		var url = $(this).attr('href');
		reloadData('#{{ $pageModule }}',url);
		return false ;
	});

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

    $("a.orderReceiptAction").click(function (e){
        var btn = $(this),
            id = btn.data('id'),
            checkUrl = siteUrl + '/order/check-receivable/'+id;

        e.preventDefault();

        btn.prop('disabled', true);
        blockUI();
        $.ajax({
            type: "GET",
            url: checkUrl,
            success: function (data) {
                unblockUI();
                if(data.status === 'success'){
                    location.href = data.url;
                }
                else {
                    btn.prop('disabled', false);
                    notyMessageError(data.message);
                    e.preventDefault();
                }
            }
        });
    });

    $(".postToNetSuitAction").on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true);
        var id = $(this).data('id');
        blockUI();
        $.ajax({
            type: "GET",
            url: "{{ url() }}/order/expose-api/"+id,
            success: function (data) {
                unblockUI();
                if(data.status === 'success'){
                    notyMessage(data.message);
                    btn.remove();
                    ajaxFilter('#{{ $pageModule }}', '{{ $pageModule }}/data');
                }
                else {
                    btn.prop('disabled', false);
                    notyMessageError(data.message);
                }
            }
        });
        $('.tooltip').hide();
    });


    $(".verifyInvoiceAction").on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true);
            var id = $(this).data('id');
            blockUI();
            $.ajax({
                type: "GET",
                url: "{{ url() }}/order/verify-invoice/"+id,
                success: function (data) {
                    unblockUI();
                    if(data.status === 'success'){
                        notyMessage(data.message);
                        btn.remove();
                        ajaxFilter('#{{ $pageModule }}', '{{ $pageModule }}/data');
                    }
                    else {
                        btn.prop('disabled', false);
                        notyMessageError(data.message);
                    }
                }
            });
            $('.tooltip').hide();
        });

       // setTimeout(function(){
        $("select[name='status_id']").each(function(){
            $(this).append('<option value="removed"> Removed</option>')
        });
      //  },500);
});
</script>

