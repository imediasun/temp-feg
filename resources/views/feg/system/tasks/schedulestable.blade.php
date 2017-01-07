<table class="schedulesTable">
    <thead>
    <tr>
        <th>#</th>
        <th>Status</th>
        <th>Created</th>
        <th>Schedule</th>
        <th>Run at</th>
        <th>End at</th>
        <th width="30%">Result</th>
        <!--<th>Result</th>-->
    </tr>
    </thead>
    <tbody>

@foreach ($schedules as $index => $row)    
    {{--*/ $id = @$row->id /*--}}
    {{--*/ $taskId = @$row->task_id /*--}}
    {{--*/ $is_active = @$row->is_active == 1 /*--}}
    {{--*/ $status_code = @$row->status_code /*--}}
    {{--*/ $status_name = @$row->status_name /*--}}
    {{--*/ $isManual = @$row->is_manual == 1 /*--}}
    {{--*/ $isTEST = @$row->is_test_mode == 1 /*--}}

    {{--*/ $scheduled_at = @$row->scheduled_at /*--}}
    {{--*/ $run_at = @$row->run_at /*--}}
    {{--*/ $end_at = @$row->end_at /*--}}
    {{--*/ $created_at = @$row->created_at /*--}}

    {{--*/ $params = @$row->params /*--}}
    {{--*/ $results = @$row->results /*--}}
    {{--*/ $notes = @$row->notes /*--}}
        
    <tr data-scheduleId="{{ $id }}"
        class="@if($is_active) active @endif 
         @if(!$is_active && $status_code == 0) deactivated @endif  
         @if($is_active && $status_code == 0) scheduled @endif 
         @if($status_code == 1) running @endif 
         @if($status_code == 9) completed @endif 
         @if($status_code == 5) error @endif 
         @if($isManual) manual @endif 
        ">
        <td>{{ $index+1 }}</td>
        <td>{{ $status_name }}
            @if($isTEST) [TEST] @endif 
            @if($isManual) (Manual) @endif             
        </td>
        <td>{{ $created_at }}</td>
        <td>{{ $scheduled_at }}</td>
        <td>{{ $run_at }}</td>
        <td>{{ $end_at }}
            @if($status_code==1)
            <button class="red-bg terminateRunningTask" 
                    data-id='{{ $id }}' 
                    data-taskid='{{ $taskId }}' 
                    title="Send Terminate signal">x</button>
            @endif </td>
        <td>
            @if($status_code==1)
            <label><input type='checkbox' 
                    data-id='{{ $id }}' 
                    data-taskid='{{ $taskId }}'
                    class='scheduleStatusAutoLoad'/> 
                Autoload status if available
            </label>
            @endif 
            <div class='resultContent'>{{ $results }}</div></td>
        <!--<td>{{ $results }}</td>-->
    </tr>

@endforeach
    </tbody>
</table>