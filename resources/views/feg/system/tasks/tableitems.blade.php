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
            <div class="panel-heading clearfix">                
                <div class="isActiveContainer pull-left">
                    <input type="checkbox"
                           data-size="small"                            
                           data-on-color="primary" data-off-color="danger"
                           data-wrapper-class="isActiveToggleForm" 
                           name="isActive" class="isActive test toggleSwitch"                            
                           @if($isActive) checked @endif>                    
                </div>
                <div class="formContent hidden">
                    <input type="hidden" class="taskId"  value="{{ $taskId }}" name="taskId" >
                    <input type="text" class="taskName" 
                           value="{{ $taskName }}"
                           name="taskName" placeholder="Task Name">
                    <input type="text" class="taskAction" 
                           value="{{ $actionName }}"
                           name="taskAction" placeholder="Task Action">
                </div>
                <div class="textContent clearfix">                    
                    <p title="Task Name" class="taskNameText pull-left">{{ $taskName }}                         
                        <span title="Task Action" class="label taskActionText">{{ $actionName }}</span>                         
                    </p>
                    <button class="btn btn-warning runTaskNow textContent pull-right"  data-taskid="{{ $taskId }}">Run Now</button>
                </div>
            </div>
            <div class="panel-body clearfix">
                <div class="taskScheduleContainer m-b">
                    <div class="formContent hidden">
                        Every xx minute xx hours xx day xx month xx weekday xx years
                    </div>
                    <div class="textContent clearfix">
                        
                    </div>
                </div>
                <div class="taskConfig">
                    
                </div>                
            </div>
            <div class="panel-footer clearfix">
                <div class="col-sm-8">
                    <p class="pull-left m-r">Last Run: <span class="label">{{ $lastRun or 'Never' }}</span></p>
                    <p class="pull-left m-r">Next Run: <span class="label">{{ $nextRun or 'Not Scheduled' }}</span></p>
                </div>
                <div class="pull-right m-b">
                    <button class="btn testTask" title="Check whether the Task Action exists"  data-taskid="{{ $taskId }}">Test</button>
                    <div class="saveButtonsGroup hidden" >
                        <button class="btn btn-default cancelEditTask" type="reset"  data-taskid="{{ $taskId }}">Cancel</button>
                        <button class="btn btn-success addUpdateTask"  data-taskid="{{ $taskId }}">Save</button>
                    </div>
                    <div class="editButtonGroup" >
                        <button class="btn btn-primary editTask"  data-taskid="{{ $taskId }}">Edit</button>                    
                        <button class="btn btn-danger deleteTask"  data-taskid="{{ $taskId }}">Delete</button>
                        <button class="btn btn-info showSchedules" title="Show all the previously executed and upcoming tasks" data-taskid="{{ $taskId }}">Schedules</button>                
                    </div>                                    
                </div>
                <div class="schedulesContainer">
                    
                </div>                
            </div>
        </form>
        </div>
        

