<?php
namespace App\Library;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\CronHelpers;
use App\Library\MyLog;

class Elm5Tasks
{   
    public static $L = null;
    public static $CL = null;
    const TASKSDB = 'elm5_tasks';
    const SCHEDULESDB = 'elm5_task_schedules';

    public static function runTasks() {
        global $_scheduleId;
        self::log("Run tasks start");  
        $now = date('Y-m-d H:i:s');
        
        register_shutdown_function(function(){
            global $_scheduleId;
            if (!empty($_scheduleId)) {
                $errors = [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE];
                $error = error_get_last();
                $eType = $error['type'];
                if (!empty($_scheduleId) && in_array($eType, $errors)) {
                    
                    $eFile = $error['file'];
                    $eLine = $error['line'];
                    $errorMessage = $error['message']. "in file $eFile line $eLine";

                    self::errorSchedule($_scheduleId);
                    self::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                    self::logScheduleFatalError($errorMessage, $_scheduleId);
                    self::log("FATAL Error running task with schedule ID: $_scheduleId");
                    self::log("Error: ".$errorMessage);    
                }
            }
            
        });
        
        $schedules = self::getRunEligibleSchedules();
        foreach ($schedules as $item) {
            
            $no_overlap = $item->no_overlap;
            $taskId = $item->task_id;
            $scheduleId = $item->id;
            $_scheduleId = $scheduleId;
            $taskName = $item->task_name;
            $is_manual = $item->is_manual == 1;
            
            $taskLog = "task '$taskName' ($taskId) [$scheduleId]";
            
            if ($no_overlap) {
                if (self::isTaskRunning($taskId)) {
                    self::log("Task '$taskName' ($taskId) is alrady running - not allowed to run overlapped. Hence, not running.");
                    continue;
                }
            }
            
            self::log("Start running ".($is_manual ? "manual ": "").$taskLog);
           
            try {
                
                $return = self::runTask($item);
                
            } catch(\Exception $e) {                
                
                $errorMessage = $e->getMessage();
                self::errorSchedule($scheduleId);
                self::updateSchedule($scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                self::logScheduleError($item, $e);
                self::log("Error running ".($is_manual ? "manual ": ""). $taskLog);
                self::log("Error: ".$errorMessage);
            }            
               
            self::log("End running ".($is_manual ? "manual ": ""). $taskLog);
            if (date('s')>= 58) {
                break;
            }
            
            $_scheduleId = null;
        }
        
        self::log("Run Tasks end");         
    }
        
    public static function runTask($item) {
        
        $now = date("Y-m-d H:i:s");
        $id = $item->id;
        
        $taskId = $item->task_id;
        $taskName = trim($item->task_name);
        $taskFolderName = preg_replace('/\-{1,}/', '-', preg_replace('/[^\w\-]/', '', str_replace(' ', '-', $taskName)));
        $actionName = $item->action_name;

        $schedule = $item->scheduled_at;
        $no_overlap = $item->no_overlap;
        $is_manual = $item->is_manual == 1;

        $success_action = $item->success_action;
        $fail_action = $item->fail_action;
        $log_folder = $item->log_folder;
        $log_filename = $item->log_filename;
        $is_test_mode = $item->is_test_mode;
        
        $logTaskId = "[$id] ($taskId) '$taskName'".($is_manual?"(manual)": "")." ($actionName)";

        if (empty($log_folder)) {
            $log_folder = "FEGCronTasks/$taskFolderName";
            $item->log_folder = $log_folder;
        }
        if (empty($log_filename)) {
            $log_filename = "task-" .(empty($is_manual) ? "" : "manual-"). strtolower($taskFolderName).".log";
            $item->log_filename = $log_filename;
        }
        
        $L = new MyLog($log_filename, $log_folder, $taskName);
        
        $initialParameters = array();
        $initialParameters['_task'] = $item;
        $initialParameters['_logger'] = $L;
        
        $params = $item->params;
        $isEmptyParam = empty($params);
        $isParamIndexedArray = strpos(trim($params), "[") === 0;
        if ($isEmptyParam) {
            $params = "[]";
        }
        
        $paramsWithNoArrayLiteralBoundary = strpos(trim($params), "[") === FALSE &&
                                            strpos(trim($params), "{") === FALSE;
        if ($paramsWithNoArrayLiteralBoundary) {
            $params = "{".$params."}";
        }
        $customParameters = json_decode($params, true);
        if ($isParamIndexedArray) {
            $parameters = array_merge($customParameters, $initialParameters);
        }
        else {
            $parameters = array(array_merge($customParameters, $initialParameters));
        }
        
        
        self::cronlog("Starting Task - $logTaskId", null, $L);
        self::startSchedule($id);       
        
        if (true || !$is_manual) {
            self::runPrePostTasks($taskId, $parameters);
        }
        
        $isError = false;
        if (!is_callable($actionName, false)) {
            $isError = true;
            $result = "ERROR: Task Action $actionName Does not exist";
        }
        else {
            try {
                $result = call_user_func_array($actionName, $parameters);
                if ($result === false) {
                    $isError = true;
                    $result = "Error: calling $actionName";
                }
                else {
                    if (!$is_manual) {
                        self::addRunCount($taskId, $taskName);
                    }                    
                    if ($result !== 0 && $result !== "0" && empty($result)) {
                        $result = "";
                    }
                }
            } catch (\Exception $ex) {
                $isError = true;
                $result = $ex->getMessage();                
                self::logScheduleError($item, $ex);
            }
        }
                
        
        if ($isError) {
            self::errorSchedule($id);    
            self::updateSchedule($id, array("notes" => $result));
            $log = "Task ERROR - $logTaskId";            
        }        
        else {
            $log = "Task result - $logTaskId";            
        }        
        self::cronlog($log, $result, $L);
        
        if (!$isError) {
            if (true || !$is_manual) {
                self::runPrePostTasks($taskId, $parameters, true);
            }
        }
        
        if (!$isError) {               
            self::endSchedule($id);
        }
        
        self::updateSchedule($id, array("results" => $result));
        
        self::cronlog("Task Ended ".($isError?"with error":"")." - $logTaskId", null, $L);
        
        return $result;
    }
        
    public static function runPrePostTasks($taskId, $parameters, $isPostTask = false) {
                
        $uL = isset($parameters['_logger']) ? $parameters['_logger'] : (
                isset($parameters[0]['_logger']) ? $parameters[0]['_logger'] : 
                    null);
        
        $q = "SELECT id, task_name, action_name, success_action, fail_action, log_folder, log_filename from " . 
                self::TASKSDB . 
                " WHERE is_active=1 AND ". ($isPostTask ? 'run_after' : 'run_before') . "=$taskId";
                
        $tasksData = DB::select($q);        
        if (count($tasksData) > 0) {
            
            foreach($tasksData as $task) { 
                $taskLogId = ($isPostTask ? 'Post' : 'Pre') . " Dependent Task - [$taskId => {$task->id}] '{$task->task_name}' ({$task->action_name})";
                self::cronlog("Starting $taskLogId", null, $uL);
                self::runDependentTask($task, $parameters);
                self::cronlog("End $taskLogId", null, $uL);
            }            
        }
    }

    public static function runDependentTask($item, $oldParams = null) {
        $now = date("Y-m-d H:i:s");
        $taskId = $item->id;
        $taskName = trim($item->task_name);
        $taskFolderName = preg_replace('/\-{1,}/', '-', preg_replace('/[^\w\-]/', '', str_replace(' ', '-', $taskName)));
        $actionName = $item->action_name;
        $success_action = $item->success_action;
        $fail_action = $item->fail_action;
        $log_folder = $item->log_folder;
        $log_filename = $item->log_filename;
        
        $uL = isset($oldParams['_logger']) ? $oldParams['_logger'] : (
                isset($oldParams[0]['_logger']) ? $oldParams[0]['_logger'] : 
                    null);
        $uTask = isset($oldParams['_task']) ? $oldParams['_task'] : (
                isset($oldParams[0]['_task']) ? $oldParams[0]['_task'] : 
                    null);
        $uSId = !empty($uTask->id) ? $uTask->id : '';
        $uTId = !empty($uTask->task_id) ? $uTask->task_id : '';        
        $uIsManual = !empty($uTask->is_manual);        

        if (empty($log_folder)) {
            $log_folder = "FEGCronTasks/$taskFolderName";
            $item->log_folder = $log_folder;
        }
        if (empty($log_filename)) {
            $log_filename = "task-". (empty($is_manual) ? "" : "manual-"). strtolower($taskFolderName).".log";
            $item->log_filename = $log_filename;
        }
        
        $logTaskId = "[$uSId] ($uTId => $taskId) '$taskName' ".($uIsManual?"(manual)": "")." ($actionName)";
        
        $L = new MyLog($log_filename, $log_folder, $taskName);
        
        $parameters = $oldParams;
        
        self::runPrePostTasks($taskId, $parameters);
        
        $isError = false;
        if (!is_callable($actionName, false)) {
            $isError = true;
            $result = "ERROR: Dependent Task Action ($actionName) Does not exist";
        }
        else {
            try {
                $result = call_user_func_array($actionName, $parameters);
                if ($result === false) {
                    $isError = true;
                    $result = "Error calling $actionName";
                }
                elseif ($result !== 0 && $result !== "0" && empty($result)) {
                    $result = "";
                }
            } catch (\Exception $ex) {
                $isError = true;
                $result = $ex->getMessage();
                self::logScheduleError($uTask, $ex);
                self::errorSchedule($uSId);       
                self::updateSchedule($uSId, array("notes" => $result, "results" => $result));        
            }
        }
        
        if ($isError) {
            self::cronlog("Dependent Task ERROR - $logTaskId", $result, $L, $uL);                
        }        
        else {
            self::cronlog("Dependent Task result - $logTaskId", $result, $L, $uL);            
        }        
        
        if (!$isError) {
            self::runPrePostTasks($taskId, $parameters, true);
        }
    }

    public static function getTasks($activeOnly = false, $scheduledOnly = false, $cronable = false) {
        
        $q = implode("", array("SELECT * from ", 
                                self::TASKSDB , 
                                " WHERE id IS NOT NULL",  
                                ($activeOnly ? " AND is_active=1 " : ""), 
                                ($scheduledOnly ? " AND TRIM(schedule) != '' " : ""), 
                                ($cronable ? " AND (
                                        (is_repeat=1 AND 
                                        (repeat_count=0 OR run_count < repeat_count))
                                        OR is_repeat=0 AND run_count = 0) " : "")
                ));
        
        $data = DB::select($q);
        return $data;        
    }
    
    public static function getTask($id, $returnAsArrayOfTasks = false) {
        $q = "SELECT * from " . self::TASKSDB . " WHERE id IN ($id)";
        $data = DB::select($q);
        if (!$returnAsArrayOfTasks && count($data) > 0) {
            $data = $data[0];
        }        
        return $data;
    }    
    
    public static function getTaskCrontab($taskId) {
        
        $q = "SELECT schedule from " .self::TASKSDB. 
                " WHERE id IN ($taskId)";
        $item = DB::select($q);
        $schedule = '';
        if ($item && count($item) > 0) {
            $schedule = $item[0]->schedule;
        }
        
        return $schedule;
    }
    
    public static function getTaskLastRunAt($id) {
       $sql = "SELECT concat(DATE_FORMAT(run_at, '%a %b %d, %Y %r'), ' (', status_name, ')') as last_run
            FROM elm5_task_schedules 
            WHERE task_id = $id AND is_manual=0 AND status_code > 0
                ORDER BY run_at desc LIMIT 1";
         
        $value = "Never";
        $data = DB::select($sql);
        if (!empty($data) && isset($data[0])) {
            $value = $data[0]->last_run;
        }

        return $value;        
    }
    
    public static function getTaskNextScheduledAt($id) {
        
        $now = date("Y-m-d H:i:s");
        $sql = "SELECT DATE_FORMAT(scheduled_at, '%a %b %d, %Y %r') as next_run
            FROM elm5_task_schedules 
            WHERE task_id = $id AND scheduled_at >= '$now'
                AND is_manual=0 AND status_code = 0 and is_active =1
                ORDER BY run_at asc LIMIT 1";
        
        $value = "";
        $data = DB::select($sql);
        if (!empty($data) && isset($data[0])) {
            $value = $data[0]->next_run;
        }
        
        if (empty($value) && !empty(trim(self::getTaskCrontab($id))) && self::isTaskActive($id)) {
            $schedule = self::addSchedule($id);
            if ($schedule) {
                $value = date("l M d, Y h:i A", strtotime($schedule));
            }
        }
        
        if (empty($value)) {
            $value = "Not scheduled";
        }
        return $value;        
    }
        
    public static function isTaskActive($taskId) {
        
        $q = "SELECT is_active from " .self::TASKSDB. 
                " WHERE id IN ($taskId) AND is_active=1";
        $activeItems = DB::select($q);
        $isActive = count($activeItems) > 0;         
        return $isActive;
    }
    
    public static function isTaskRunning($taskId) {
        
        $q = "SELECT id from " .self::SCHEDULESDB. 
                " WHERE task_id IN ($taskId) AND is_active=1 AND status_code > 0";
        $runningItems = DB::select($q);
        $isRunning = count($runningItems) > 0; 
        
        return $isRunning;
    }
    
    public static function isTaskManualRunning($taskId) {
        
        $q = "SELECT id from " .self::SCHEDULESDB. 
                " WHERE task_id IN ($taskId) AND is_active=1 AND is_manual=1 ";
        $runningItems = DB::select($q);
        $isRunning = count($runningItems) > 0; 
        
        return $isRunning;
    }
    
    public static function deleteTask($id) {
        self::deleteTaskSchedule($id);
        return DB::delete("DELETE FROM ".self::TASKSDB .
                " where id IN ($id)");        
        
    }
    
    public static function disableTask($id) {
        self::deactivateTaskSchedule($id);
        $q = "UPDATE ".self::TASKSDB .
                " set is_active=0
                    where id IN ($id)";
        return DB::update($q);
    }
     
    public static function addRunCount($taskId, $taskName) {
        $q = "UPDATE ".self::TASKSDB .
                " SET run_count=run_count+1
                    where id IN ($taskId)";
        DB::update($q);
        self::log("Increment Run count for task '$taskName' ID: $taskId");
        self::runCountManager($taskId, $taskName);
    }
    
    public static function runCountManager($taskId, $taskName) {        
        // is_repeat = 0
        // repeat_count 
        // run_count
        $q = "SELECT is_repeat, repeat_count, run_count FROM ".self::TASKSDB .                
                    " where id IN ($taskId)";
        $rows = DB::select($q);
        if ($rows && count($rows) > 0) {
            $row = $rows[0];
            $isRepeat = $row->is_repeat;
            $repeatLimit = $row->repeat_count;
            $runCount = $row->run_count;
            if ($isRepeat == 0 || ($repeatLimit > 0 && $runCount >= $repeatLimit) ) {
                self::log("Disable Task as Run Limit reached for task '$taskName' ID: $taskId");
                self::disableTask($taskId);
            }
        }        
    }  
    
    

    public static function addSchedule($taskId, $isRunNow = false) {
        $override = array();
        self::log("Add schedule start");  
        
        $insertData = array();
        
        if (is_array($taskId)) {
            $override = $taskId;
            $taskId = $override['id'];
        }
        
        $item = self::getTask($taskId);
        
        if ($item && !empty($item)) {
                    
            $taskId = $item->id;
            $taskName = $item->task_name;
            $schedule = trim($item->schedule);
            $no_overlap = $item->no_overlap;
            $success_action = $item->success_action;
            $fail_action = $item->fail_action;
            $log_folder = $item->log_folder;
            $log_filename = $item->log_filename;
            $is_test_mode = $item->is_test_mode;
            $is_manual = $isRunNow ? 1 : 0;
            $params = '';
            
            $scheduled_at = null;
            if ($isRunNow) {
                $scheduled_at = date('Y-m-d H:i:s', strtotime("now -1 minute"));
            }
            else {
                if ($schedule) {
                    $scheduled_at = CronHelpers::getNextRunDate($schedule);
                }                
            }
            
            if (!empty($override['scheduledat'])) {
                $scheduled_at = $override['scheduledat'];
            }
            if (!empty($override['onsuccess'])) {
                $success_action = $override['onsuccess'];
            }
            if (!empty($override['onfailure'])) {
                $fail_action = $override['onfailure'];
            }
            if (!empty($override['logfolder'])) {
                $log_folder = $override['logfolder'];
            }
            if (!empty($override['logfile'])) {
                $log_filename = $override['logfile'];
            }            
            if (!empty($override['params'])) {
                $params = $override['params'];
            }
            if (isset($override['istestmode'])) {
                $is_test_mode = $override['istestmode'];
            }
            
            $notScheduled = true;
            if (!empty($scheduled_at)) {                
                $q = "SELECT * from " .self::SCHEDULESDB. "
                    WHERE task_id = $taskId AND is_active = 1 and is_manual=$is_manual " .
                        (empty($is_manual) ? " AND scheduled_at = '$scheduled_at' " : "");
                $scheduledTask = DB::select($q);
                if ($scheduledTask && count($scheduledTask) > 0) {
                    $notScheduled = false;
                }
            
                if ($notScheduled) {
                    self::log(($isRunNow ? "[RUN NOW] ":"") . 
                            "New schedule for Task '$taskName'($taskId) - at $scheduled_at");  
                    $insertData = array(
                        "task_id" => $taskId,
                        "is_manual" => $is_manual,
                        "scheduled_at" => $scheduled_at,
                        "no_overlap" => $no_overlap,
                        "success_action" => $success_action,
                        "fail_action" => $fail_action,
                        "log_folder" => $log_folder,
                        "log_filename" => $log_filename,
                        "status_name" => 'Scheduled',
                        "is_active" => 1,
                        "is_test_mode" => $is_test_mode,                        
                        "params" => $params,
                    );

                    if (count($insertData) > 0) {
                        DB::table(self::SCHEDULESDB)->insert($insertData);
                    }            
                }
                else {
                    self::log(($isRunNow ? "[RUN NOW ]":"") . 
                            "Not scheduled as active schedule exists"); 
                }
            }
            
        }
        
        self::log("Add schedule end");          
        
        return $scheduled_at;
    }
    
    public static function stopBrokenTasks() {        
        $now = date("Y-m-d H:i:s");
        $q = "UPDATE " .self::SCHEDULESDB . " 
            SET is_active = 0, end_at='$now', 
                status_name = 'Error', status_code = 5, 
                notes = 'Running too long'
            WHERE 
                is_active = 1 AND status_code = 1 AND 
                run_at < DATE_SUB('$now', INTERVAL 23 HOUR)
            ";
        $updateCount = DB::update($q);   
        if ($updateCount > 0) {
            self::log("Terminated $updateCount tasks which were running too long");
        }
    }
    public static function addSchedules() {
        
        self::log("Add automatic schedules start");  
        
        self::stopBrokenTasks();
        
        $insertData = array();
        
        $q = "SELECT * from " .self::TASKSDB . " 
            WHERE is_active=1 
                AND TRIM(schedule) != ''
                AND (
                (is_repeat=1 AND (repeat_count=0 OR run_count < repeat_count))
                OR is_repeat=0 AND run_count = 0)
            ";
        
        $data = DB::select($q);
        
        foreach ($data as $item) {
            
            $taskId = $item->id;
            $taskName = $item->task_name;
            $schedule = trim($item->schedule);
            $no_overlap = $item->no_overlap;
            $success_action = $item->success_action;
            $fail_action = $item->fail_action;
            $log_folder = $item->log_folder;
            $log_filename = $item->log_filename;
            $is_test_mode = $item->is_test_mode;
            
            $scheduled_at = CronHelpers::getNextRunDate($schedule);
            
            $notScheduled = true;
            $q = "SELECT * from " .self::SCHEDULESDB. "
                    WHERE task_id = $taskId AND scheduled_at = '$scheduled_at' 
                    AND is_active = 1 AND is_manual = 0
                ";
                        
            $scheduledTask = DB::select($q);
            if ($scheduledTask && count($scheduledTask) > 0) {
                $notScheduled = false;
            }
            
            if ($notScheduled) {
                self::log("New automatic schedule for Task '$taskName' - at $scheduled_at");  
                $insertData[] = array(
                    "task_id" => $taskId,
                    "status_name" => 'Scheduled',
                    "is_active" => 1,
                    "scheduled_at" => $scheduled_at,
                    "no_overlap" => $no_overlap,
                    "success_action" => $success_action,
                    "fail_action" => $fail_action,
                    "log_folder" => $log_folder,
                    "is_test_mode" => $is_test_mode,
                );
            }            
        }
        
        if (count($insertData) > 0) {
            DB::table(self::SCHEDULESDB)->insert($insertData);
        }
        
        self::log("Add automatic schedules end");  
    }

    public static function updateSchedule($id, $data) {
        DB::table(self::SCHEDULESDB)
            ->where('id', $id)
            ->update($data);
    }
    
    public static function startSchedule($id) {
        global $_scheduleId;
        if (empty($id)) {
            $id = $_scheduleId;
        }
        $now = date("Y-m-d H:i:s");
        self::updateSchedule($id, array("status_code" => 1, "run_at" => $now, "status_name" => 'Running')); 
    }
    
    public static function endSchedule($id) {
        global $_scheduleId;
        if (empty($id)) {
            $id = $_scheduleId;
        }
        $now = date("Y-m-d H:i:s");
        self::updateSchedule($id, array("status_code" => 9, "end_at" => $now, "status_name" => 'Completed', "is_active" => 0)); 
    }
    
    public static function errorSchedule($id) {
        global $_scheduleId;
        if (empty($id)) {
            $id = $_scheduleId;
        }
        $now = date("Y-m-d H:i:s");
        //DB::connection()->reconnect();
        self::updateSchedule($id, array("status_code" => 5, "end_at" => $now, "status_name" => 'Error', "is_active" => 0));      
    }
    
    public static function deleteSchedule($id) {
        return DB::delete("DELETE FROM ".self::SCHEDULESDB .
                " where id IN ($id) AND status_code = 0");
    }
    
    public static function deactivateSchedule($id) {
        return DB::update("UPDATE ".self::SCHEDULESDB .
                " set is_active=0, notes='Deactivated'  
                    where id IN ($id) AND status_code = 0 AND is_manual != 1");
    }
    
    public static function deleteTaskSchedule($id) {
        return DB::delete("DELETE FROM ".self::SCHEDULESDB .
                " where task_id IN ($id) AND status_code = 0");
    }
    
    public static function deactivateTaskSchedule($id) {
        $q = "UPDATE ".self::SCHEDULESDB .
                " set is_active=0, notes='Deactivated' 
                    where task_id IN ($id) AND status_code = 0 AND is_manual != 1";
        return DB::update($q);
    }

    
    /**
     * 
     * @param type $task_id
     * @param type $scheduledOnly
     * @param type $includeManual
     * @param type $includeInactive
     * @param type $onlyEligibleForRun
     * @return type
     */
    public static function getSchedules($task_id = null, $scheduledOnly = false, 
            $includeManual = true, $includeInactive = false, $onlyEligibleForRun = false) {
        
        $now = date('Y-m-d H:i:s');
        $q = "SELECT  S.id, S.task_id, T.task_name, T.action_name, T.repeat_count, T.run_count,
                      S.is_active, S.is_test_mode,
                      S.scheduled_at, S.is_manual, S.no_overlap, S.params,
                      S.success_action, S.fail_action,
                      S.log_folder, S.log_filename, S.results
                      
            FROM " .self::SCHEDULESDB. " S
                
            INNER JOIN elm5_tasks T ON T.id = S.task_id
            
            WHERE T.id IS NOT NULL" .
                (!empty($task_id) ? " AND S.task_id IN ($task_id)" : " ") .
                ($scheduledOnly ? " AND (S.status_code=0 OR S.status_code IS NULL)": "") .
                ($includeManual ? "": " AND is_manual != 1") .
                ($includeInactive ? "" : ($includeManual ? " AND S.is_active = 1":" AND T.is_active=1 AND S.is_active = 1")) .
                ($onlyEligibleForRun ? " AND (S.scheduled_at IS NOT NULL AND S.scheduled_at <= '$now')": "") .
                    
            " ORDER BY S.scheduled_at ASC, S.is_manual DESC
            ";        
        
        return DB::select($q);
    }
    
    public static function getRunEligibleSchedules($task_id = null) {        
        return self::getSchedules($task_id, true, true, false, true);
    }
    
    public static function getActiveSchedules($task_id = null) {        
        return self::getSchedules($task_id, false, true, false, false);
    }
    
    public static function getAllSchedules($task_id = null) {        
        return self::getSchedules($task_id, false, true, true, false);
    }
    
    public static function getSchedulesForReview($task_id = null, $scheduledOnly = false, 
            $includeManual = true, $includeInactive = true, $onlyEligibleForRun = false) {
        
        $now = date('Y-m-d H:i:s');
        $q = "SELECT  S.id, S.task_id, 
                      T.task_name, T.action_name, T.repeat_count, T.run_count,
                      S.is_manual, S.no_overlap, S.params,
                      S.is_active, S.is_test_mode,
                      S.status_code, S.status_name,
                      S.scheduled_at, S.run_at, S.end_at, S.created_at, S.results, S.notes,                      
                      S.success_action, S.fail_action,
                      S.log_folder, S.log_filename
                      
            FROM " .self::SCHEDULESDB. " S
                
            INNER JOIN elm5_tasks T ON T.id = S.task_id
            
            WHERE T.id IS NOT NULL" .
                (!empty($task_id) ? " AND S.task_id IN ($task_id)" : " ") .
                ($scheduledOnly ? " AND (S.status_code=0 OR S.status_code IS NULL)": "") .
                ($includeManual ? "": " AND is_manual != 1") .
                ($includeInactive ? "" : ($includeManual ? " AND S.is_active = 1":" AND T.is_active=1 AND S.is_active = 1")) .
                ($onlyEligibleForRun ? " AND (S.scheduled_at IS NOT NULL AND S.scheduled_at <= '$now')": "") .
                    
            " ORDER BY S.scheduled_at DESC
                LIMIT 100
            ";                
        return DB::select($q);
    }

    
    
    private static function log($message = '', $data = '', $L = null, $uL = null) {
        if (is_null(self::$L)) {
            self::$L = new MyLog("task-manager.log", "FEGCronTasks", "FEG Cron Tasks");
        }
        self::$L->log($message, $data);
        if (!empty($L)) {
            $L->log($message, $data);
        }
        if (!empty($uL)) {
            $uL->log($message, $data);
        }
    }
    
    private static function cronlog($message = '', $data = '', $L = null, $uL = null) {        
        if (is_null(self::$CL)) {
            self::$CL = new MyLog("task-manager-cron.log", "FEGCronTasks", "FEG Cron Tasks");
        }
        self::$CL->log($message, $data);
        if (!empty($L)) {
            $L->log($message, $data);
        }
        if (!empty($uL)) {
            $uL->log($message, $data);
        }        
    }
    
    private static function logScheduleError($item, $e) {
        
        $taskId = $item->task_id;
        $scheduleId = $item->id;
        $taskName = $item->task_name;
        $errorMessage = $e->getMessage();
        $errorFile = $e->getFile();
        $errorLine = $e->getLine();
        $errorTrace = str_replace('\\\\', "\\", 
                str_replace('\\r', "\r", 
                str_replace('\\n', "\n", 
                str_replace('\\t', "\t", 
                str_replace('\\r\\n', "\r\n", 
                json_encode($e->getTrace(), JSON_UNESCAPED_SLASHES))))));
        
        $generalErrorMessage = "Task error while running task '$taskName' ($taskId), Schedule ID - $scheduleId";

        $eL = new MyLog("task-error.log", "FEGCronTasks", "FEG Cron Tasks");
        $eL->error($generalErrorMessage);
        $eL->error('Message: '. $errorMessage);
        $eL->error('File: '. $errorFile . " (line: $errorLine)");        
        $eL->error('Trace: '.$errorTrace);    
        
        self::emailScheduleError($item, $e);
        
    }
    private static function logScheduleFatalError($errorMessage, $scheduleId) {
        
        
        $generalErrorMessage = "FATAL ERROR while running task with schedule ID $scheduleId";

        $eL = new MyLog("task-error-FATAL.log", "FEGCronTasks", "FEG Cron Tasks");
        $eL->error($generalErrorMessage);
        $eL->error('Message: '. $errorMessage);
        self::emailScheduleError($item, $e);
        self::emailScheduleFatalError($errorMessage, $scheduleId);
        
    }
    
    private static function emailScheduleError($item, $e) {
        
        $taskId = $item->task_id;
        $scheduleId = $item->id;
        $taskName = $item->task_name;
        $errorMessage = $e->getMessage();
        $errorFile = $e->getFile();
        $errorLine = $e->getLine();
        $errorTrace = str_replace('\\\\', "\\", 
                str_replace('\\r', "\r", 
                str_replace('\\n', "\n", 
                str_replace('\\t', "\t", 
                str_replace('\\r\\n', "\r\n", 
                json_encode($e->getTrace(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))))));
        
        $generalErrorMessage = "Task error while running task '$taskName' ($taskId), Schedule ID - $scheduleId";                
    }    
    private static function emailScheduleFatalError($errorMessage, $scheduleId) {
        
        $generalErrorMessage = "FATAL ERROR while running task with schedule ID $scheduleId";                
    }    
}
