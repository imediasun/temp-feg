<div class="content">
        @foreach ($rowData as $row)
            {{--*/ $taskId = $row->id /*--}}
            {{--*/ $taskName = $row->task_name /*--}}
            {{--*/ $actionName = $row->action_name /*--}}
            {{--*/ $isActive = $row->is_active /*--}}
            {{--*/ $params = $row->params /*--}}
            {{--*/ $schedule = $row->schedule /*--}}
            {{--*/ $is_repeat= $row->is_repeat /*--}}
            {{--*/ $repeat_count= $row->repeat_count /*--}}
            {{--*/ $no_overlap = $row->no_overlap /*--}}
            {{--*/ $run_after = $row->run_after /*--}}
            {{--*/ $run_before = $row->run_before /*--}}
            {{--*/ $fail_action = $row->fail_action /*--}}
            {{--*/ $success_action = $row->success_action /*--}}
            {{--*/ $fail_email = $row->fail_email /*--}}
            {{--*/ $success_email = $row->success_email /*--}}
            {{--*/ $run_count = $row->run_count /*--}}
            {{--*/ $notes = $row->notes /*--}}
            {{--*/ $log_folder = $row->log_folder /*--}}
            {{--*/ $log_filename = $row->log_filename /*--}}
            {{--*/ $schedules = $row->schedules = null /*--}}
            {{--*/ $lastSchedule = $row->lastSchedule = null /*--}}
            {{--*/ $nextSchedule = $row->nextSchedule = null /*--}}
        <form action="{{ $pageUrl }}/save/?taskid={{ $taskId }}" method="post">
        <div class="taskPanel panel @if($isActive) panel-active @else panel-inactive @endif" data-taskid="{{ $taskId }}">
            <div class="panel-heading">
                <div class="formContent hidden">
                    <input type="hidden" class="taskId"  value="{{ $taskId }}" name="taskId" >
                    <input type="text" class="taskName" 
                           value="{{ $taskName }}"
                           name="taskName" placeholder="Name">
                    <input type="text" class="taskAction" 
                           value="{{ $actionName }}"
                           name="taskAction" placeholder="Action">
                </div>
                <div class="textContent clearfix">
                    <p class="taskNameText pull-left">{{ $taskName }} 
                        <span class="label label-info taskActionText">{{ $actionName }}</span>                         
                    </p>
                    <button class="btn btn-warning runTaskNow textContent pull-right"  data-taskid="{{ $taskId }}">Run Now</button>
                </div>
            </div>
            <div class="panel-body clearfix">
            </div>
            <div class="panel-footer clearfix">
                <div class="col-sm-8">
                    
                </div>
                <div class="pull-right">
                    <div class="saveButtonsGroup hidden" >
                        <button class="btn btn-default cancelEditTask" type="reset"  data-taskid="{{ $taskId }}">Cancel</button>
                        <button class="btn btn-success addUpdateTask"  data-taskid="{{ $taskId }}">Save</button>
                    </div>
                    <div class="editButtonGroup" >
                        <button class="btn btn-primary editTask"  data-taskid="{{ $taskId }}">Edit</button>                    
                        <button class="btn btn-danger deleteTask"  data-taskid="{{ $taskId }}">Delete</button>
                        <button class="btn btn-info showSchedules"  data-taskid="{{ $taskId }}">Schedules</button>                
                    </div>
                </div>
            </div>
        </div>
        </form>
        @endforeach
	 <?php echo Form::open(array('url'=>$pageUrl.'/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">
    @if(!empty($topMessage))
    <h5 class="topMessage">{{ $topMessage }}</h5>
    @endif
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" data-module="{{ $pageModule }}" data-url="{{ $pageUrl }}">
        <thead>
			<tr>
                @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
				<th width="35"> No </th>
                @endif
                @if($setting['disableactioncheckbox']=='false')
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
                                    ' align="'.$t['align'].'"'.
                                    ' width="'.$t['width'].'"';
							$th .= '>';
                            $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                            $th .= '</th>';
                            echo $th;
                        }
					endif;
				endforeach; ?>
                @if($setting['disablerowactions']=='false')
				<th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
                @endif
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
                    @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
					<td class="number"> <?php echo ++$i;?>  </td>
                    @endif
                    @if($setting['disableactioncheckbox']=='false')
					<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                    @endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url($pageModule.'/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') :
							$conn = (isset($field['conn']) ? $field['conn'] : array() );


							$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
									{!! $value !!}
								 </td>
							@endif
                    <?php
						 endif;
						endforeach;
					  ?>
                  @if($setting['disablerowactions']=='false')     
				 <td data-values="action" data-key="<?php echo $row->id ;?>">
					{!! AjaxHelpers::buttonAction($pageDetails, $access, $id, $setting) !!}
					{!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
				</td>
                @endif
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
