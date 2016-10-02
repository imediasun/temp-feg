        {{--*/ $taskId = @$row->id /*--}}
        {{--*/ $taskName = @$row->task_name /*--}}
        {{--*/ $actionName = @$row->action_name /*--}}
        {{--*/ $isActive = @$row->is_active /*--}}
        {{--*/ $params = @$row->params /*--}}
        {{--*/ $schedule = @$row->schedule /*--}}
        {{--*/ $is_repeat= @$row->is_repeat /*--}}
        {{--*/ $repeat_count= @$row->repeat_count /*--}}
        {{--*/ $no_overlap = @$row->no_overlap /*--}}
        {{--*/ $run_after = @$row->run_after /*--}}
        {{--*/ $run_before = @$row->run_before /*--}}
        {{--*/ $fail_action = @$row->fail_action /*--}}
        {{--*/ $success_action = @$row->success_action /*--}}
        {{--*/ $fail_email = @$row->fail_email /*--}}
        {{--*/ $success_email = @$row->success_email /*--}}
        {{--*/ $run_count = @$row->run_count /*--}}
        {{--*/ $notes = @$row->notes /*--}}
        {{--*/ $log_folder = @$row->log_folder /*--}}
        {{--*/ $log_filename = @$row->log_filename /*--}}
        {{--*/ $schedules = $row->schedules = null /*--}}
        {{--*/ $lastSchedule = $row->lastSchedule = null /*--}}
        {{--*/ $nextSchedule = $row->nextSchedule = null /*--}}
        
        <div class="taskPanel taskPanel-{{ $taskId }} panel @if($isActive) panel-active @else panel-inactive @endif" data-taskid="{{ $taskId }}">
        <div class="ajaxLoading"></div>
        <form action="{{ $pageUrl }}/save/?taskid={{ $taskId }}" method="post" class="taskForm taskForm-{{ $taskId }}">
            <div class="panel-heading">                
                <div class="formContent hidden">
                    <input type="checkbox"  data-class="" data-size="small" data-toggle="toggle" data-on="Active" data-off="In Active" name="isActive" class="isActive test" data-onstyle="success" @if($isActive) checked @endif>                    
                    <input type="hidden" class="taskId"  value="{{ $taskId }}" name="taskId" >
                    <input type="text" class="taskName" 
                           value="{{ $taskName }}"
                           name="taskName" placeholder="Name">
                    <input type="text" class="taskAction" 
                           value="{{ $actionName }}"
                           name="taskAction" placeholder="Action">
                </div>
                <div class="textContent clearfix">                    
                    <input type="checkbox" data-class="pull-left"  data-size="small" data-toggle="toggle" data-on="Active" data-off="In Active" class="isActive test" data-onstyle="success" disabled @if($isActive) checked @endif>
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
        </form>
        </div>
        

