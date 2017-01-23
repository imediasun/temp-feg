<div class="mainContent">
    <div class="alertContent"></div>
    <div class="addButtonContent m-b">
        <button class="btn btn-info btn-lg addNewTask">Add New Task</button>
    </div>
    <div class="content tasksContent">
        @foreach ($rowData as $row)
            @include('feg.system.tasks.tableitems', array('row' => $row))
        @endforeach
    </div>
    <div class='hidden taskTemplateContent'>
        @include('feg.system.tasks.tableitems', array('row' => new StdClass))
    </div>
    <div class='hidden runTaskPopupTemplateContent'>
        <form >
        <input type="hidden" class="taskId" name="taskId" value="" /> 
        <div class="clearfix m-t-xs">
            <div class="col-sm-2">
                Schedule: 
                <input type="text"  name="scheduledat" class="scheduledat form-control"
                       placeholder='2016-11-35 15:30:00'
                       data-toggle="tooltip" data-placement="top" 
                       title="Optional date for schedule in YYYY-MM-DD HH:MM:SS (24 hour) format" 
                       value="" /> 
            </div>
            <div class="col-sm-10">
                Parameters: 
                <input type="text"  name="params" class="params form-control"
                       placeholder='{ "date": "2016-11-35", "location": 7001 }'
                       data-toggle="tooltip" data-placement="top" 
                       title="Parameters in JSON format" 
                       values='{ "date": "2016-11-25", "location": 2000 }'
                       value=''
                       /> 
            </div>
        </div>
        <div class="clearfix m-t-xs">
            <div class="col-sm-2 m-t-md">  
                <label class="red-bg">
                <input type="checkbox"  name="isTestMode" class="test isTestMode"
                       data-toggle="tooltip" data-placement="top" 
                       value="1"
                       title="A special flag send to the task - developer can make use of it for testing like sending email to testers only"  /> 
                TEST MODE?
                </label>
                <label class="">
                <input type="checkbox"  name="runDependent" class="test runDependent"
                       data-toggle="tooltip" data-placement="top" 
                       value="1"
                       title="Uncheck not to run the dependent tasks"  /> 
                Dependents?
                </label>                
            </div>                    
            <div class="col-sm-2">
                Log Folder: 
                <input type="text"  name="logfolder" class="logfolder form-control"
                       data-toggle="tooltip" data-placement="top" 
                       title="A directory relative to SITEROOT/storage/logs/ for example: 'transfer/daily' " 
                       value="" /> 
            </div>                    
            <div class="col-sm-2">
                Log file: 
                <input type="text"  name="logfile" class="logfile form-control" value="" /> 
            </div>
            <div class="col-sm-3">
                Success Action: 
                <input type="text"  name="onsuccess" class="onsuccess form-control" 
                       data-toggle="tooltip" data-placement="top" 
                       title="Action to run when task runs successfully" 
                       value="" /> 
            </div>                    
            <div class="col-sm-3">
                Fail Action: 
                <input type="text"  name="onfailure" class="onfailure form-control" 
                       data-toggle="tooltip" data-placement="top" 
                       title="Action to run when task fails" 
                       value="" /> 
            </div>
        </div>
          
        <div class="clearfix m-t-xs">
            <div class="col-sm-12">
                <button class="submit btn btn-success" >Confirm</button>
                <button class="cancel btn btn-default" type="reset">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</div>
