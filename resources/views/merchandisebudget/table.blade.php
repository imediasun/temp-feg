<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','merchandisebudget/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','merchandisebudget/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
			@if(Session::get('gid') == \App\Models\Core\Groups::SUPPER_ADMIN)
			<a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
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
            {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
        </div>
        @endif
        @endif
        @include( $pageModule.'/toolbar')

	 <?php echo Form::open(array('url'=>'merchandisebudget/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">
    @if(!empty($topMessage))
    <h5 class="topMessage">{{ $topMessage }}</h5>
    @endif    
	@if(count($rowData)>=1)
    <table class="table table-striped  datagrid" id="{{ $pageModule }}Table">
        <thead>
			<tr>
                @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
				<th width="35"> No </th>
                @endif                				
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
                <th width="150">October</th>
                <th width="150">November</th>
                <th width="150">December</th>
                @if($setting['disablerowactions']=='false')
				<th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
                @endif
			  </tr>
        </thead>

        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0" >
				<td class="cell"> # </td>
                @if($setting['disableactioncheckbox']=='false' && ($access['is_remove'] == 1 || $access['is_add'] =='1'))
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
                <td class="cell" data-form="budget_year" data-form-type="textarea">
                    <input type="text" name="budget_year" class="form-control input-sm" value="">
                </td>
                <td class="cell">
                    <button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
                </td>
            </tr>
            @endif

           		<?php foreach ($rowData as $row) :
           			  $id = $row->id;
                ?>
                <tr class="editable" id="form-{{ $row->id }}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') data-id="{{ $row->id }}" ondblclick="showFloatingCancelSave(this)" @endif>
					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
					<td class="number"> <?php echo ++$i;?>  </td>
                    @endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('merchandisebudget/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif


                    <td data-values="{{$row->location}}" data-field="location" data-format="{{$row->location}}">{{ $row->location }}</td>
                    <td style="display: none" data-values="{{\Session::get('budget_year')}}" data-field="budget_year" data-format="{{\Session::get('budget_year')}}">{{\Session::get('budget_year')}}</td>
                    <td data-values="{{$row->Jan}}" data-field="jan" data-format="{{$row->Jan}}">{{ $row->Jan }}</td>
                    <td data-values="{{$row->Feb}}" data-field="feb" data-format="{{$row->Feb}}">{{ $row->Feb }}</td>
                    <td data-values="{{$row->March}}" data-field="march" data-format="{{$row->March}}">{{ $row->March }}</td>
                    <td data-values="{{$row->April}}" data-field="april" data-format="{{$row->April}}">{{ $row->April }}</td>
                    <td data-values="{{$row->May}}" data-field="may" data-format="{{$row->May}}">{{ $row->May }}</td>
                    <td data-values="{{$row->June}}" data-field="june" data-format="{{$row->June}}">{{ $row->June }}</td>
                    <td data-values="{{$row->July}}" data-field="july" data-format="{{$row->July}}">{{ $row->July }}</td>
                    <td data-values="{{$row->August}}" data-field="august" data-format="{{$row->August}}">{{ $row->August }}</td>
                    <td data-values="{{$row->September}}" data-field="september" data-format="{{$row->September}}">{{ $row->September }}</td>
                    <td data-values="{{$row->October}}" data-field="october" data-format="{{$row->October}}">{{ $row->October }}</td>
                    <td data-values="{{$row->November}}" data-field="november" data-format="{{$row->November}}">{{ $row->November }}</td>
                    <td data-values="{{$row->December}}" data-field="december" data-format="{{$row->December}}">{{ $row->December }}</td>



				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::buttonAction('merchandisebudget',$access,$id ,$setting) !!}

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
            @if($setting['inline']!='false' && $setting['disablerowactions']=='false')
                @foreach ($rowData as $row)
                    {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                @endforeach
            @endif
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

	</div>
</div>

	@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
$(document).ready(function() {
	$('.tips').tooltip();
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
});
</script>
<style>
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>
