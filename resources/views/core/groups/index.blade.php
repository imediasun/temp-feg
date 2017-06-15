@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} </h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 
	<ul class="nav nav-tabs" style="margin-bottom:10px;">
	  <li ><a href="{{ URL::to('core/users')}}"><i class="fa fa-user"></i> Users </a></li>
	  <li class="active"><a href="{{ URL::to('core/groups')}}"><i class="fa fa-users"></i> Groups</a></li>
	  <li class=""><a href="{{ URL::to('core/users/blast')}}"><i class="fa fa-envelope"></i> Send Email </a></li>
	</ul>	

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h5>
		<div class="sbox-tools" >
        {{--*/ $sortParam = is_null(Input::get('sort'))?'':'&sort='.Input::get('sort') /*--}}
        {{--*/ $orderParam = is_null(Input::get('order'))?'':'&order='.Input::get('order') /*--}}
        {{--*/ $rowsParam = is_null(Input::get('rows'))?'':'&rows='.Input::get('rows') /*--}}
        @if(isset($_GET['search']))
		 <a href="{{ url($pageModule) }}?{{ $sortParam }}{{ $orderParam }}{{ $rowsParam }}"
            class="btn btn-xs btn-white tips btn-search" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
        @endif
		@if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
			<a href="{{ URL::to('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
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
	    <div class="toolbar-line c-margin">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('core/groups/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
			<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
			@endif 		
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('core/groups/download') }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
			<i class="fa fa-download "></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
			@endif			
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'core/groups/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table id="coregroupsTable" class="table table-striped table-width-auto datagrid">
        <thead>
			<tr>
				@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
				<th class="number"> No </th>

				@endif

					@if($setting['disableactioncheckbox']=='false')
				<th width="30px"> <input type="checkbox" class="checkall" /></th>
					@endif
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
				<th width="70" >{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>

        <tbody>
						
            @foreach ($rowData as $row)
                <tr>
					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
					<td> {{ ++$i }}</td>
					@endif
						@if($setting['disableactioncheckbox']=='false')

					<td><input type="checkbox" class="ids" name="id[]" value="{{ $row->group_id }}" /></td>
						@endif
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 <td>					 
					 	@if($field['attribute']['image']['active'] =='1')
							{{ SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) }}
						@else	
							{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
							{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}	
						@endif						 
					 </td>
					 @endif					 
				 @endforeach
				 <td>
					 	@if($access['is_detail'] ==1)
						<a href="{{ URL::to('core/groups/show/'.$row->group_id.'?return='.$return)}}" class="tips btn btn-xs btn-white" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ URL::to('core/groups/update/'.$row->group_id.'?return='.$return) }}" class="tips btn btn-xs btn-white" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
						@endif
												
					
				</td>				 
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
		$('#SximoTable').attr('action','{{ URL::to("cpre/groups/multisearch")}}');
		$('#SximoTable').submit();
	});
	
    var simpleSearch = $('.simpleSearchContainer'),
        ajaxMode = false;
        
    if (simpleSearch.length) {
        initiateSearchFormFields(simpleSearch);
        simpleSearch.find('.doSimpleSearch').click(function(event){
            performSimpleSearch.call($(this), {
                moduleID: '#{{ $pageModule }}',
                url: "{{ $pageUrl }}",
                event: event,
                ajaxSearch: ajaxMode,
                container: simpleSearch
            });
        });
    }
    
    initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}', {useAjax: ajaxMode});    
    
    updateNativeUIFieldsBasedOn();  
    makeSimpleSearchFieldsToInitiateSearchOnEnter();
});	
</script>		
@stop
