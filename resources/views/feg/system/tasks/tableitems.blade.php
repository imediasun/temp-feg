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
                    <input type="checkbox" data-handle-width="35"
                           data-size="small"                            
                           data-on-color="primary" data-off-color="danger"
                           data-wrapper-class="isActiveToggleForm" 
                           name="isActive" class="isActive test toggleSwitch"                            
                           @if($isActive) checked @endif 
                           readonly >                    
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
                    <div class="clearfix cronscheduletext m-b">
                        <strong>Run: </strong><span class="cronStampText" data-cronstamp="{{ $schedule }}"></span> 
                    </div>
                    <div class="formContent hidden clearfix">
                        <div class="form-inline clearfix cronscheduleinputs">
                            <label><strong>Edit: </strong></label>
                            <?php 
                                $cronItems = array();
                                if ($schedule) {
                                    $cronItems = explode(' ', $schedule);
                                }
                                $cronMin = isset($cronItems[0]) ? $cronItems[0] : '';
                                $cronHr = isset($cronItems[1]) ? $cronItems[1] : '';
                                $cronDay = isset($cronItems[2]) ? $cronItems[2] : '';
                                $cronMonth = isset($cronItems[3]) ? $cronItems[3] : '';
                                $cronWeekday = isset($cronItems[4]) ? $cronItems[4] : '';
                                $cronYear = isset($cronItems[5]) ? $cronItems[5] : '';

                            ?>
                            <input type="text" name="cronmin" value="{{ $cronMin }}" placeholder="Minute" class="cronmin croninp">
                            <input type="text" name="cronhr" value="{{ $cronHr }}" placeholder="Hour" class="cronhr croninp">
                            <input type="text" name="cronday" value="{{ $cronDay }}" placeholder="Day" class="cronday croninp">
                            <input type="text" name="cronmonth" value="{{ $cronMonth }}" placeholder="Month" class="cronmonth croninp">
                            <input type="text" name="cronweekday" value="{{ $cronWeekday }}" placeholder="Weekday" class="cronweekday croninp">
                            <!--<input type="text" name="cronyear" value="{{ $cronYear }}" placeholder="Year" class="cronyear croninp">-->
                            <input type="hidden" name="cronstamp" value="{{ $schedule }}" >
                        </div>
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
                    
                    <div class="saveButtonsGroup hidden" >
                        <button class="btn btn-default cancelEditTask" type="reset"  data-taskid="{{ $taskId }}">Cancel</button>
                        <button class="btn testTask" title="Check whether the Task Action exists"  data-taskid="{{ $taskId }}">Test</button>
                        <button class="btn btn-success addUpdateTask"  data-taskid="{{ $taskId }}">Save</button>
                    </div>
                    <div class="editButtonGroup" >
                        <button class="btn btn-primary editTask"  data-taskid="{{ $taskId }}">Edit</button>                    
                        <button class="btn testTask" title="Check whether the Task Action exists"  data-taskid="{{ $taskId }}">Test</button>
                        <button class="btn btn-danger deleteTask"  data-taskid="{{ $taskId }}">Delete</button>
                        <button class="btn btn-info showSchedules" title="Show all the previously executed and upcoming tasks" data-taskid="{{ $taskId }}">Schedules</button>                
                    </div>                                    
                </div>
                <div class="schedulesContainer">
                    
                </div>                
            </div>
        </form>
        </div>
        

