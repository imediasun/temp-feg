@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
		@if(Session::get('gid') ==10)
			<a href="{{ URL::to('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('ordertyperestriction/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
			<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
			@endif 
			<a href="{{ URL::to( 'ordertyperestriction/search/native') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('ordertyperestriction/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
			<i class="fa fa-download"></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
			@endif			
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'ordertyperestriction/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table table-striped ">
        <thead>
			<tr>
				<th class="number" style="width: 20px;"> No </th>
				{{--<th> <input type="checkbox" class="checkall" /></th>--}}
				
				@foreach ($tableGrid as $t)
					<?php if($t['view'] =='1') :				
						$limited = isset($t['limited']) ? $t['limited'] :'';
						if(SiteHelpers::filterColumn($limited ))
						{
							echo '<th align="'.$t['align'].'" width="'.$t['width'].'">'.\SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())).'</th>';				
						} 
					endif;?>
				@endforeach
				{{--<th width="70" >{{ Lang::get('core.btn_action') }}</th>--}}
			  </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
                <tr>
					<td > {{ ++$i }} </td>
					{{--<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />--}}  </td>
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
							
						 <?php
							$conn = (isset($field['conn']) ? $field['conn'] : array() );
							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn,'');
						 	?>



							@if($field['field']=='api_restricted')
								 <td>
								 <input type='checkbox' name="actionSwitch" @if($value == "1") checked  @endif 	data-size="mini" data-animate="true"
										data-on-text="Active" data-off-text="Inactive" data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
										id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />
								</td>
							 @else
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
									 {!! $value !!}
								 </td>
							 @endif
						
						@endif	
					 @endif					 
				 @endforeach
				 {{--<td>
					 	--}}{{--@if($access['is_detail'] ==1)
						<a href="{{ URL::to('ordertyperestriction/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ URL::to('ordertyperestriction/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
						@endif--}}{{--

					 <input type='checkbox' name="actionSwitch" @if($value == "Yes") checked  @endif 	data-size="mini" data-animate="true"
							data-on-text="Active" data-off-text="Inactive" data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
							id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />
												
					
				</td>	--}}
                </tr>
				
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#SximoTable').attr('action','{{ URL::to("ordertyperestriction/multisearch")}}');
		$('#SximoTable').submit();
	});
	$("[name='actionSwitch']").bootstrapSwitch();});


	$("[id^='toggle_trigger_']").on('switchChange.bootstrapSwitch', function(event, state) {
	var typeId=$(this).data('id');
	var message = '';
	var check = false;
	if(state)
	{
		message = "<div class='confirm_inactive'><br>Are you sure you want to Active this <br> <b>***WARNING***</b><br> if you active this User then he will be able to login and to do any task.</div>";
	}
	else
	{
		check = true;
		message = "<div class='confirm_inactive'><br>Are you sure you want to Inactive this <br> <b>***WARNING***</b><br> if you inactive this User then he will be unable to login and to do any task.</div>";
	}

	currentElm = $(this);
	currentElm.bootstrapSwitch('state', check,true);
	$('.custom_overlay').show();
	App.notyConfirm({
		message: message,
		confirmButtonText: 'Yes',
		confirm: function (){
			$('.custom_overlay').slideUp(500);
			$.ajax({
				type:'POST',
				url:'ordertyperestriction/trigger',
				data:{isActive:state,typeId:typeId},
				success:function(data){
					currentElm.bootstrapSwitch('state', !check,true);
					if(data.status == "error"){
						notyMessageError(data.message);
					}
				}
			}
			);
		},
		cancel: function () {
			$('.custom_overlay').slideUp(500);
		}
	});

});


</script>		
@stop