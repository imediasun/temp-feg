<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','merchandisebudget/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','merchandisebudget/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') ==1)
			<a href="{{ url('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
			@endif
		</div>
	</div>
	<div class="sbox-content">

        @include( $pageModule.'/toolbar')

	 <?php echo Form::open(array('url'=>'merchandisebudget/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">
	@if(count($rowData)>=1)
    <table class="table table-striped  " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th width="20"> No </th>
				<th width="30"> <input type="checkbox" class="checkall" /></th>
                <th width="200">Location</th>
                <th width="150">January</th>
                <th width="150">February</th>
                <th width="150">March</th>
                <th width="150">April</th>
                <th width="150">May</th>
                <th width="150">June</th>
                <th width="150">July</th>
                <th width="150">August</th>
                <th width="150">September</th>
                <th width="150">Octuber</th>
                <th width="150">November</th>
                <th width="150">December</th>

				<th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
			  </tr>
        </thead>

        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0" >
				<td> # </td>
				<td> </td>
				@if($setting['view-method']=='expand') <td> </td> @endif
				@foreach ($tableGrid as $t)

				<td >
					<button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
				</td>
			  </tr>
                @endforeach
			  @endif

           		<?php foreach ($rowData as $row) :
           			  $id = $row->id;
                ?>
                <tr class="editable" id="form-{{ $row->id }}">
					<td class="number"> <?php echo ++$i;?>  </td>
					<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('merchandisebudget/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
                    <td>{{ $row->location }}</td>
                    <td>{{ $row->Jan }}</td>
                    <td>{{ $row->Feb }}</td>
                    <td>{{ $row->March }}</td>
                    <td>{{ $row->April }}</td>
                    <td>{{ $row->May }}</td>
                    <td>{{ $row->June }}</td>
                    <td>{{ $row->July }}</td>
                    <td>{{ $row->August }}</td>
                    <td>{{ $row->September }}</td>
                    <td>{{ $row->Octuber }}</td>
                    <td>{{ $row->November }}</td>
                    <td>{{ $row->December }}</td>

				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::buttonAction('merchandisebudget',$access,$id ,$setting) !!}
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
	<?php echo Form::close() ;?>
	@include('ajaxfooter')

	</div>
</div>

	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
$(document).ready(function() {
	$('.tips').tooltip();
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
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
