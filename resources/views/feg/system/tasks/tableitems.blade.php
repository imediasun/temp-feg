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
                <div class="row">
                <div class="taskScheduleContainer col-lg-6">
                    <div class="clearfix cronscheduletext">
                        <strong>Run: </strong><span class="cronStampText" data-cronstamp="{{ $schedule }}"></span> 
                    </div>
                    <div class="formContent hidden clearfix m-t">
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
                            <input type="text" name="cronmin" value="{{ $cronMin }}" 
                                data-toggle="tooltip" data-placement="top" 
                                title="Minute (range 0-59 or *) Examples: 10 for every 10th minute. 10,15,45 for every 10th, 15th and 45th minute. 10-15 for every 10th to 15th minute. * for every minute. */5 for every 5 minutes. */2 for every even minutes. 1-*/2 for every odd minutes. 11-31/7 for every 7 minutes starting 11th minute and ending 31st minute (11,18,25)." 
                                placeholder="Minute" class="cronmin croninp">
                            <input type="text" name="cronhr" value="{{ $cronHr }}" 
                                data-toggle="tooltip" data-placement="top" 
                                title="Hour (range 0-23 or *) Examples: 10 for every 10th hour. 8,10,20 for every 8th, 10th and 20th hour. 10-15 for every 10th to 15th hour. * for every hour. */5 for every 5 hours. */2 for every even hours. 1-*/2 for every odd hours. 11-19/2 for every alternate hours starting 11th hour and ending 19th hour (11,13,15,17,19)." 
                                placeholder="Hour" class="cronhr croninp">
                            <input type="text" name="cronday" value="{{ $cronDay }}" 
                                data-toggle="tooltip" data-placement="top" 
                                title="Day (range 1-31 or *) Examples: 10 for every 10th day of the month. 10,15,25 for every 10th, 15th and 25th day of month. 10-15 for every 10th to 15th day of month. * for every month. */5 for every 5 days. */2 for every even days. 1-*/2 for every odd days. 11-22/6 for every 6 days starting 11th day and ending 22th day (11,17,23)." 
                                placeholder="Day" class="cronday croninp">
                            <input type="text" name="cronmonth" value="{{ $cronMonth }}" 
                                data-toggle="tooltip" data-placement="top" 
                                title="Month (range 1-12 or *) Examples: 8 for every Aug. 3,10,11 for every Mar, Oct and Nov. 5-8 for every May,Jun,July,Aug. * for every month. */5 for every 5th month (May and Oct). */2 for every even months (Feb,Apr,Jun,Aug,Oct,Dec). 1-*/2 for every odd months (Jan, Mar, May, Jul, Sep, Nov). 5-12/3 for every 3 months starting 5th month (May) and ending 12th month (Dec) - May,Aug and Nov ." 
                                placeholder="Month" class="cronmonth croninp">
                            <input type="text" name="cronweekday" value="{{ $cronWeekday }}" 
                                data-toggle="tooltip" data-placement="top" 
                                title="Weekday (range 0-6 where 0 is sunday or  *) Examples: 3 for every Wed. 2,3,6 for every Tue, Wed and Sat. 3-6 for every Wed,Thu,Fri,Sat. * for every day in week. */5 for every 5 minutes. */2 for every even weekdday (Sun,Tue,Thu,Sat). 1-5/2 for every odd day in week (Mon,Wed,Fri). " 
                                placeholder="Weekday" class="cronweekday croninp">
                            <!--<input type="text" name="cronyear" value="{{ $cronYear }}" 
                            data-toggle="tooltip" data-placement="top" 
                                title="Year (range four digit year or *) Examples:  * for all years, 2017 for year 2017. 2008-2022 for years 2008 to 2022. */2 every even year. 1-*/2 every odd year." 
                                placeholder="Year" class="cronyear croninp">-->
                            <input type="hidden" name="cronstamp" value="{{ $schedule }}" >
                        </div>
                    </div>
                </div>
                <div class="taskConfig col-lg-6">
                    
                </div>                
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
        

