<div class="row m-b">
	<div class="col-md-8">
			@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxCopy('#{{ $pageModule }}','{{ $pageUrl }}')"><i class="fa fa-file-o"></i> Copy </a>
			@endif 
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemove('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
			@endif 	
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>
                @if(SiteHelpers::isModuleEnabled($pageModule))
                    <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
                    @if(!empty($colconfigs))
                        <select class="form-control" style="width:25%!important;display:inline;" name="col-config"
                                id="col-config">
                            <option value="0">Select Configuraton</option>
                            @foreach( $colconfigs as $configs )
                                <option @if($config_id == $configs['config_id']) selected
                                                                                 @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
                            @endforeach
                        </select>
                    @endif
                @endif
                </div>
	<div class="col-md-4 ">
		@if($access['is_excel'] ==1)
		<div class="pull-right">
			<a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
			<a href="{{ URL::to( $pageModule .'/export/word?return='.$return) }}" class="btn btn-sm btn-white"> Word </a>
			<a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
			<a href="{{ URL::to( $pageModule .'/export/print?return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
		</div>	
		@endif
	</div>
</div>
<div class="row">
    <div class="col-md-3"><select name='product_type' rows='5' id='product_type' class='select3'></select></div>
    <div class="col-md-6">
        {!! Form::open(array('url'=>'product/listcsv', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
        <div class="col-md-2"><h3> Export </h3></div>
        <div class="col-md-6">
             <select name='vendor_id' rows='5' id='vendor_id' class='select3'></select>
         </div>
        <div class="col-md-2">
             <button type="submit" class="btn btn-primary">Export To CSV</button>
        </div>
            {!! Form::close() !!}
    </div>
</div><br/>
<script>
    $(document).ready(function(){
        $("#product_type").jCombo("{{ URL::to('product/comboselect?filter=product_type:id:product_type') }}",
                {  selected_value : '{{ $product_type_id }}',initial_text:'--- Select Product Type ---'});
        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name') }}",
                {  selected_value : '',initial_text:'--- Select Vendor ---'});
        $(".select3").select2({ width:"98%"});
    });
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
  $("#product_type").click(function()
  {
     var val=$("#product_type").val();
      if(val) {
          reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?product_type='+val);
      }
  });
</script>
