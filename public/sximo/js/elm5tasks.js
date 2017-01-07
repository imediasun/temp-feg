(function(){
var UNDEFINED, ajax , UNFN = function(){};

jQuery(document).ready(function ($) {
    $(document).on('click', '.runTaskNow', initRunTaskNow);
    $(document).on('click', '.cancelEditTask', initCancelEditTask);
    $(document).on('click', '.addUpdateTask', updateTask);
    $(document).on('click', '.editTask', initEditTask);
    $(document).on('click', '.deleteTask', initDelTask);
    $(document).on('click', '.showSchedules', initShowScheduledTasks);
    $(document).on('click', '.addNewTask', initAddTask);
    $(document).on('click', '.testTask', testTask);
    $(document).on('click', '.expandTask', expandTaskContainer);
    $(document).on('click', '.collapseTask', collapseTaskContainer);
    $(document).on('click', '.logActionsExpand', logActionsExpand);
    $(document).on('change', '.croninp', buildCrontab);
    $(document).on('click', '.terminateRunningTask', sendTerminateTaskSignal);
    $(document).on('click', '.scheduleStatusAutoLoad', autoLoadScheduleStatus);
    
    populateTaskDropdowns();
    
    initTasks($('.tasksContent'));    
});


function autoLoadScheduleStatus(e) {
    
    var elm = jQuery(this),
        id = elm.attr('data-id'),
        parent = elm.closest('td'),
        target = parent.find('.resultContent'),
        isChecked = elm.prop('checked'),
        intervalId = elm.data('autoloadIntervalId'),
        data = {'id': id},
        successFn = function (result) {
            var i, h = result;
            if (typeof result != 'string') {
                h = '';
                for(var i in result) {
                   h += result[i] + "<br>";
                }
            }
            
            target.html(h);            
        },
        options = {'method': 'GET', success: successFn, error: UNFN },        
        url = pageUrl + '/schedulestatus';
    
    if (isChecked) {
        if (intervalId) {
            clearInterval();
        }
        intervalId = setInterval(function(){
            callServer(url, data, UNFN, options);
        }, 5000);
        elm.data('autoloadIntervalId', intervalId);
    }
    else {
        clearInterval(intervalId);
    }
    
}


function sendTerminateTaskSignal(e) {
    e.preventDefault();
    var elm = jQuery(this),
        id = elm.attr('data-id'),
        data = {'id': id},
        options = {'method': 'POST', success: UNFN, error: UNFN },        
        url = pageUrl + '/terminateschedule';

    callServer(url, data, UNFN, options);
    elm.hide();
}

function logActionsExpand(e) {
    e.preventDefault();
    var elm = jQuery(this),
        task = elm.closest('.taskPanel'),
        container = task.find('.logActionsEdit');
    container.slideDown();    
    elm.hide();
}

function expandTaskContainer(e) {
    e.preventDefault();
    var elm = jQuery(this),
        task = elm.closest('.taskPanel'),
        oppositeButton = task.find('.collapseTask'),
        container = task.find('.panel-body, .panel-footer');
    container.slideDown(function(){
        
    });
    elm.hide();
    oppositeButton.show();
    
}

function collapseTaskContainer(e) {
    e.preventDefault();
    var elm = jQuery(this),
        task = elm.closest('.taskPanel'),
        oppositeButton = task.find('.expandTask'),
        container = task.find('.panel-body, .panel-footer, .schedulesContainer');
    container.slideUp(function(){
        oppositeButton.show();
    });    
    elm.hide();
}
function populateTaskDropdowns(elm) {
    if (!elm) {
        elm = jQuery("select[name=run_after], select[name=run_before]");
    }
    
    var option,
        options = [],
        optionsHtml = '',
        i,
        l = tasksList && tasksList.length,
        item,
        task,
        taskId,
        taskName;

    if (l) {
        for(i=0; i < l; i++) {
            task = tasksList[i];
            if (task) {
                taskId = task.id;
                taskName = task.task_name;
                option = ["<option value='",taskId,"'",">",task.task_name,"</option>"].join('');
                options.push(option);                            
            }
            
        }
        optionsHtml = options.join('');
    }
        
        
    if (elm.length) {
        elm.each(function(){
            var dropdown = jQuery(this),
                parent = dropdown.closest('.taskPanel'),
                
                beforeTaskTextElm = parent.find('.runBeforeTaskText'),
                beforeTaskId = beforeTaskTextElm.attr('data-runTask'),
                beforeTaskText = tasksList['t'+beforeTaskId] && tasksList['t'+beforeTaskId].task_name || 'None',
                afterTaskTextElm = parent.find('.runAfterTaskText'),
                afterTaskId = afterTaskTextElm.attr('data-runTask'),
                afterTaskText = tasksList['t'+afterTaskId] && tasksList['t'+afterTaskId].task_name || 'None',
                
                selfTaskId = parent.find('input.taskId').val() || '',
                assignedTaskId = dropdown.attr('data-select-runTask') || '',
                
                selfTask = tasksList['t'+selfTaskId],                
                assignedTask = tasksList['t'+assignedTaskId];
                
            beforeTaskTextElm.text(beforeTaskText);
            afterTaskTextElm.text(afterTaskText);

            if (l) {
                dropdown.find("option:nth-child(n+2)").remove();
                dropdown.append(optionsHtml);
                
                if (selfTask) {
                    dropdown.find('option[value='+selfTaskId+']').remove();
                }
                if (assignedTask) {
                    dropdown.val(assignedTaskId);
                }               
            }
        });
    }
    
    
}

function initTasks(parent) {
    if (!parent) {
        parent = jQuery(document);
    }
    parseCronStamps(parent.find('.cronStampText'));
    
    parent.find('[data-toggle="tooltip"]').tooltip();
    parent.find('[name="run_dependent"]').prop('checked', true);
    
    parent.find('.toggleSwitch').bootstrapSwitch({
        onInit: switchOnInit,
        onSwitchChange: switchOnChange
    });
    
}

function serializeForm(form) {
    var data = form.serialize();
    form.find('input[type=checkbox]').each(function() {     
        if (!this.checked) {
            data += '&'+this.name+'=0';
        }
    });    
    return data;
}

function callServer(url, data, success, options) {
    
    if (!options) {
        options  = {};
    }    
    
    var ajax, 
        type = options.type,
        method = options.method,
        optionSuccess = options.success,
        optionError = options.error,
        defaultSuccess = function (data, textStatus, jqXHR) {            
            if (data && data.message) {
                if (data.status=="error") {
                    notyMessageError(data.message);
                }
                else {
                    notyMessage(data.message);
                }
            }
            if (success) {
                success(data, textStatus, jqXHR);
            }            
            if (optionSuccess) {
                optionSuccess(data, textStatus, jqXHR);
            }            
        }, 
        defaultError = function (jqXHR, textStatus, errorThrown) {            
            if (data && data.message) {
                if (data.status=="error") {
                    notyMessageError(data.message);
                }
                else {
                    notyMessage(data.message);
                }
            }            
            if (optionError) {
                optionError(jqXHR, textStatus, errorThrown);
            }
        };
    
    if (!type && method) {
        options.type = method;
    }
    if (!method && type) {
        options.method = type;
    }
    options.url = url;
    options.data = data;
    options.success = defaultSuccess;
    options.error = defaultError;
    
    try {
        ajax = jQuery.ajax(options);
    }
    catch (err) {
        notyMessageError(err);
    }
    
    return ajax;
}

function parseCronStamps(elm, value) {
    
    var getValueFromElement = !value;
    if (!elm) {
        elm = jQuery('.cronStampText');
    }
    if (getValueFromElement) {
        elm.each(function(){
            var eachElm = jQuery(this), pretty;
            if (getValueFromElement) {
                value = eachElm.attr('data-cronstamp');
                pretty = parseCronStamp(value);
                eachElm.text(pretty);
            }
        });
        
    }
    
}
function parseCronStamp(value) {    
    var cron = value.replace(/\s/g, '') ? prettyCron.toString(value) : 'None'; 
    return cron;
}
    
function switchOnInit(event, state) {
    var elm = jQuery(this);
    elm.data('resetValue', elm.prop('checked'));
}
function switchOnChange(event, state) {    
    var elm = jQuery(this),
        parent = elm.closest('.taskPanel'),
        className = state ? 'panel-active' : 'panel-inactive';
        
    parent.removeClass('panel-active panel-inactive').addClass(className);
    elm.prop('checked', state);    
}

function updateDataSourceWithUpdatedTask(id, data) {
    var index, task;
    if (!id) {
        id = data.id;
        index = tasksList.push(data) - 1;
        data.index = index;
        tasksList['t'+id] = tasksList[index];
    }
    else {
        task = tasksList['t'+id];
        index = task.index;
        data.index = index;
        tasksList[index] = data;
        tasksList['t'+id] = tasksList[index];
    }
    
    populateTaskDropdowns();
    jQuery('.addNewTask').show();
}
function updateDataSourceWithDeletedTask(id) {
    var key ='t' + id, 
        task = tasksList[key],
        index = task && task.index;

    if (task) {
        delete tasksList[key];
    }
    if (index >= 0) {
        delete tasksList[index];
    }  
    populateTaskDropdowns();
}

function runTaskPopupInit(popup, parent, taskId, success, cancel) {
    var options = {'method': 'POST', success: success, error: cancel },
        url = pageUrl + '/runnow',
        form = popup.find('form'),
        isTest = parent.find('.taskForm input[name=is_test_mode]').prop('checked'),
        runDependent = parent.find('.taskForm input[name=run_dependent]').prop('checked'),
        data,
        ajax;
    
    form.find('input.taskId').val(taskId);
    form.find('input.isTestMode').prop('checked', isTest);
    form.find('input.runDependent').prop('checked', runDependent);
    
    form.find('.cancel').unbind('click').click(function(e){
        //e.preventDefault();
        cancel();
    });
    form.find('.submit').unbind('click').click(function(e){
        e.preventDefault();
        data = serializeForm(form);
        ajax = callServer(url, data, UNFN, options);
    });
    
    popup.fadeIn();
    popup.find('[data-toggle="tooltip"]').tooltip();
    
}

function initRunTaskNow(event) {
    event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        popup = parent.find('.popupRunTaskFormCotainer'),
        popupTemplate = jQuery('.runTaskPopupTemplateContent').html(),
        form = parent.find('form'),
        taskId = form.find('.taskId').val(),        
        success = function (data) {
            cancel();
            if (data && data.statusCode) {
                //btn.remove();
            }            
        },
        cancel = function () {
            popup.fadeOut(function(){
                popup.html('');
            });
            //btn.prop('disabled', false);
        };
        
    //btn.prop('disabled', true);    
    popup.html(popupTemplate);
    runTaskPopupInit(popup, parent, taskId, success, cancel);
}

function initCancelEditTask(event) {        
    //event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        buttons = parent.find('.saveButtonsGroup, .editButtonGroup'),
        switches = parent.find('.toggleSwitch.isActive'),
        cronText = parent.find('.cronStampText'),
        cronTextResetValue = cronText.data('resetValue'),
        switchValue = switches.data('resetValue');
    toggleFormTextContent(null, parent);    
    parent.find('.taskScheduleContainer').toggleClass('shade');
    buttons.toggleClass('hidden');
    switches.prop('checked', switchValue);
    switches.bootstrapSwitch('state', switchValue);
    switches.bootstrapSwitch('readonly', true);
    
    if (cronTextResetValue) {
        cronText.text(cronTextResetValue);
    }
    
}
function updateTask(event) {   
    event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        form = parent.find('form'),
        taskId = form.find('.taskId').val(),
        cron = form.find('[name=schedule]'),        
        crontab = cron.val(),        
        uiblocker = parent.find('.ajaxLoading'),
        unblock = function (calback) {uiblocker.fadeOut(calback);},
        block = function (calback) {uiblocker.fadeIn(calback);},
        success = function (data) {
            var newParent;
            unblock();
            if (data && data.html) {
                newParent = jQuery(data.html);
                parent.replaceWith(newParent);
                initTasks(newParent);
                updateDataSourceWithUpdatedTask(taskId, data.taskData);                
            }
        },
        options = {'method': 'POST', success: success, error: unblock },
        url = pageUrl + '/save',
        data,
        ajax;
        
    if (crontab.replace(/\s/g, '')== '' || crontab === "None") {
        cron.val('');
    }
    
    block();    
    data = serializeForm(form);
    ajax = callServer(url, data, UNFN, options);
    
    //toggleFormTextContent(null, parent);    
}
function initEditTask(event) {
    event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        buttons = parent.find('.saveButtonsGroup, .editButtonGroup'),
        switches = parent.find('.toggleSwitch.isActive');
    
    parent.find('.taskScheduleContainer').toggleClass('shade');
    toggleFormTextContent(null, parent);
    buttons.toggleClass('hidden');
    switches.bootstrapSwitch('readonly', false);
}
function initDelTask(event) {
    event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        taskId = parent.find('.taskId').val(),
        taskName = parent.find('.taskNameText .taskTitle').text(),
        uiblocker = parent.find('.ajaxLoading'),
        unblock = function (calback) {uiblocker.fadeOut(calback);},
        block = function (calback) {uiblocker.fadeIn(calback);},
        success = function (data) {
            unblock();
            parent.remove();
            updateDataSourceWithDeletedTask(taskId);
        },        
        options = {'method': 'POST', success: success, error: unblock },
        url = pageUrl + '/delete',
        data = "&ids="+taskId,
        ajax;
    
    App.notyConfirm({
        message: 'Are you sure you want to delete the task - ' + taskName,
        confirmButtonText: 'Yes',
        confirm: function (){
            block();
            ajax = callServer(url, data, UNFN, options);
        }
    });
    
}
function initShowScheduledTasks(event) {
    event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        taskId = parent.find('.taskId').val(),
        container = parent.find('.schedulesContainer'),
        content = trim(container.html() || ""),
        success = function (data) {
            if (data && data.html) {
                container.html(data.html);                
            }
        },
        options = {'method': 'GET', success: success },
        url = pageUrl + '/schedules',
        data = {'id': taskId},
        ajax;

    if (container.is(":visible")) {
        container.slideUp();
    }
    else {
        container.slideDown();        
        if (true || !content) {
            ajax = callServer(url, data, UNFN, options);
        }        
    }

    
}
function initAddTask(event) {
    event.preventDefault();
    var btn = jQuery(this),
        parent = jQuery('.tasksContent'),
        source = jQuery(".taskTemplateContent .taskPanel").clone();    
    
    source.find('.panel-body, .panel-footer').slideDown();
    btn.hide();
    source.find('.textContent, .footerStatus').remove();
    source.find('.addUpdateTask').text('Add');
    
    initTasks(source); 
    source.prependTo(parent);
    source.find('.cancelEditTask').click(function(event){
        event.preventDefault();
        event.stopPropagation();    
        source.remove();
        btn.show();
    });    
    source.find('.editTask').click(); 
    source.find('.editButtonGroup').remove();
}
function testTask(event) {
    event.preventDefault();
    var btn = jQuery(this),
        parent = btn.closest('.taskPanel'),
        val = parent.find('[name=action_name]').val(),
        data = {'actionName': val},
        options = {};    
    
    callServer(pageUrl+'/testaction', data, UNFN, options);
}
function buildCrontab(e) {
    var elm = jQuery(this),
        parent = elm.closest('.taskScheduleContainer'),
        targetText = parent.find('.cronStampText'),
        targetInp = parent.find('input[name=schedule]'),
        iMin = trim(parent.find('.cronmin.croninp').val() || ''),
        iHr = trim(parent.find('.cronhr.croninp').val() || ''),
        iDay = trim(parent.find('.cronday.croninp').val() || ''),
        iMon = trim(parent.find('.cronmonth.croninp').val() || ''),
        iWeek = trim(parent.find('.cronweekday.croninp').val() || ''),
        iYear = trim(parent.find('.cronyear.croninp').val() || ''),
        val = [iMin, iHr, iDay, iMon, iWeek, iYear ].join(' '),
        pretty,
        resetPretty = targetText.data('resetValue');
    
    if (trim(val) !== '') {
        if (iYear === '') {
            iYear = '*';
        }
        if (iWeek === '') {
            iWeek = '*';
        }
        if (iMon === '') {
            iMon = '*';
        }
        if (iDay === '') {
            iDay = '*';
        }
        if (iHr === '') {
            iHr = '*';
        }
        if (iMin === '') {
            iMin = '*';
        }
    }
    if (!resetPretty) {
        targetText.data('resetValue', targetText.text());
    }
    
    val = [iMin, iHr, iDay, iMon, iWeek, iYear ].join(' ');    
    pretty = parseCronStamp(val);
    
    targetText.text(pretty);
    targetInp.val(val);        
}


function toggleFormTextContent(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main = container || jQuery(".tasksContent "+ idClass),
        forms = main.find(".formContent"),
        texts = main.find(".textContent");      
        forms.toggleClass('hidden');
        texts.toggleClass('hidden');
}
function trim(txt) {
    if (typeof txt == 'string') {
        txt = txt.replace(/^\s+?|\s+?$/g, '');
    }
    return txt;
}

}());

function hashTaskList(taskList) {
    var task,
        l = taskList.length,
        i;
    for(i=0; i < l; i++) {
        task = taskList[i];
        task.index = i;
        taskList['t' + task.id] = task;
    }
}

hashTaskList(tasksList);