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
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>
	
	
	<div class="page-content-wrapper m-t">	 

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
        {{--*/ $sortParam = is_null(Input::get('sort'))?'':'&sort='.Input::get('sort') /*--}}
        {{--*/ $orderParam = is_null(Input::get('order'))?'':'&order='.Input::get('order') /*--}}
        {{--*/ $rowsParam = is_null(Input::get('rows'))?'':'&rows='.Input::get('rows') /*--}}
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('core/pages/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
			<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
			@endif 		
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('core/pages/download') }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
			<i class="fa fa-download "></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
			@endif			
		 
		</div> 		

	
	
	 {!! Form::open(array('url'=>'core/pages/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table id="corepagesTable" class="table table-striped datagrid" >
        <thead>
			<tr>

				@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                    <th class="number" width="30"> No </th>
				@endif
                @if($setting['disableactioncheckbox']=='false')
                    <th width="50"> <input type="checkbox" class="checkall" /></th>
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
                                    ' align="'.$t['align'].'"';
                            $th .= '>';
                            $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                            $th .= '</th>';
                            echo $th;
                        }
                    endif;
                endforeach; ?>


                @if($setting['disablerowactions']=='false')
                    <th width="200"><?php echo Lang::get('core.btn_action') ;?></th>
                @endif
			  </tr>
        </thead>

        <tbody>
						
            @foreach ($rowData as $row)

                <tr>
					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                        <td width="30"> {{ ++$i }} </td>
					@endif

                    @if($setting['disableactioncheckbox']=='false')
                        <td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->pageID }}" />  </td>
                    @endif
                    @foreach ($tableGrid as $field)

					 @if($field['view'] =='1')
					 <td>					 
					 	@if($field['attribute']['image']['active'] =='1')
							{!! SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) !!}
						@else	
							{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
							{!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}	
						@endif						 
					 </td>
					 @endif					 

				 @endforeach
				 <td id="s_icons">
					 	@if($access['is_detail'] ==1)
					 		@if($row->pageID == 1)
					 		<a href="{{ url()}}" target="_blank"  class="tips btn btn-xs btn-white viewButtonOnGridRow linkToCMSPage linkPUAA" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
					 		@else
							<a href="{{ url($row->alias)}}" target="_blank" class="tips btn btn-xs btn-white viewButtonOnGridRow linkToCMSPage linkPUAA" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
							@endif
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ url('core/pages/update/'.$row->pageID.'?return='.$return) }}" class="tips btn btn-xs btn-white editButtonOnGridRow" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
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
		$('#SximoTable').attr('action','{{ URL::to("core/pages/multisearch")}}');
		$('#SximoTable').submit();
	});
    
    var ajaxMode = false;
	initDataGrid('{{ $pageModule }}', '{{ $pageUrl }}', {useAjax: ajaxMode});
});	
</script>		
@stop