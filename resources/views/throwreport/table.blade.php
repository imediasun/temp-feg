<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','throwreport/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','throwreport/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') ==1)
				<a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
			@endif
		</div>
	</div>
	<div class="sbox-content">

		@include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

		<?php echo Form::open(array('url'=>'throwreport/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
		<div class="table-responsive">
			@if(!empty($topMessage))
				<h5 class="topMessage">{{ $topMessage }}</h5>
			@endif
			@if(count($rowData)>=1)
				<table class="table table-striped  " id="{{ $pageModule }}Table">
					<thead>
					<tr>
						<th width="20"> No </th>
						@if($setting['disableactioncheckbox']=='false')
							<th width="30"> <input type="checkbox" class="checkall" /></th>
						@endif
						@if($setting['view-method']=='expand') <th>  </th> @endif
						<?php foreach ($tableGrid as $t) :
							if($t['view'] =='1'):
								$limited = isset($t['limited']) ? $t['limited'] :'';
								if(SiteHelpers::filterColumn($limited ))
								{
									echo '<th align="'.$t['align'].'" width="'.$t['width'].'">'.\SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())).'</th>';

								}
							endif;
						endforeach; ?>
						<th width="100">PC Payout</th>

						<th width="100">Cost of Goods Sold</th>
						<th width="100">Payout %</th>
						<th width="100">Overall %</th>
					</tr>
					</thead>

					<tbody>
					@if($access['is_add'] =='1' && $setting['inline']=='true')
						<tr id="form-0" >
							<td> # </td>
							@if($setting['disableactioncheckbox']=='false')
								<td> </td>
							@endif
							@if($setting['view-method']=='expand') <td> </td> @endif
							@foreach ($tableGrid as $t)
								@if($t['view'] =='1')
									<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
									@if(SiteHelpers::filterColumn($limited ))
										<td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
											{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}
										</td>
									@endif
								@endif
							@endforeach
							<td >
								<button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
							</td>
						</tr>
					@endif

					<?php foreach ($rowData as $row) :
					$id = $row->id;
					?>
					<tr class="editable" id="form-{{ $row->id }}">
						<td class="number"> <?php echo ++$i;?>  </td>
						@if($setting['disableactioncheckbox']=='false')
							<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
						@endif
						@if($setting['view-method']=='expand')
							<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('throwreport/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
						@endif
						<?php foreach ($tableGrid as $field) :
						if($field['view'] =='1') :
						$conn = (isset($field['conn']) ? $field['conn'] : array() );


						$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn);
						?>
						<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
							<td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
								@if($field['field']=='price_per_play')

									<input type="text" value="{{ $value }}" name="price_per_play[]" id="{{ $row->id }}" style="width:55px" />

								@elseif($field['field']=='retail_price')

									<input type="text" value="{{ $value }}" name="retail_price[]" class="num" style="width:55px" />



								@else {!! $value !!}
								@endif
							</td>
						@endif
						<?php
						endif;
						endforeach;
						?>
						<td><input type="text"  name ="pc_payout" value="" class="num"   style="width:55px" /></td>


						<td><input type="text" value=""  readonly id="tot" class="tot" style="width:55px" /></td>

						<td><input type="text" value="" id ="payout_percent" class ="payout_percent" style="width:55px" /></td>
						<td></td>

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
		<?php echo Form::close() ;?>
		@include('ajaxfooter')
		<div class="col-md-10 col-md-offset-3">


			<div class="col-md-10">
				<input type="button" style="font-size:1.4em; width:60%; text-align:center;"
					   value="Submit Weekly Reports"></button>
			</div>
		</div>

		</br>


	</div>
</div>

@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
$(document).ready(function() {
	$('.tips').tooltip();

	$('.num').change(function(){
		console.log($(this).val());
		var retail_price = $(this).closest('tr').find("input[name='retail_price[]']").val();
		var payout = $(this).closest('tr').find("input[name='pc_payout']").val();

		var revenue = $(this).closest('tr').find("td[data-field='revenue_total']").attr("data-values");

		if (revenue == 0.00) {

			var payout_percent = (retail_price * payout);

		}
		else {

			var payout_percent = (retail_price * payout) / revenue;
		}



			$(this).closest('tr').find("input#payout_percent").val(payout_percent);

			$(this).closest('tr').find("input#tot").val(retail_price * payout);

		});



	function totalAmount(price, quantity) {
		return price * quantity;
	}




	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
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
});
</script>
<style>
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>
