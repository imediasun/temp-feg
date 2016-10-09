jQuery(document).ready(function ($) {
    $(document).on('click', '.cancelEditTask', initCancelEditTask);
    $(document).on('click', '.addUpdateTask', updateTask);
    $(document).on('click', '.editTask', initEditTask);
    $(document).on('click', '.deleteTask', initDelTask);
    $(document).on('click', '.showSchedules', initShowScheduledTasks);
    $(document).on('click', '.addNewTask', initAddTask);
    $(document).on('change', '.croninp', buildCrontab);
    
    $('.toggleSwitch').bootstrapSwitch({
        onInit: switchOnInit,
        onSwitchChange: switchOnChange
    });
    
    parseCronStamps();    

});

function buildCrontab(e) {
    var elm = $(this),
        parent = elm.closest('.taskScheduleContainer'),
        targetText = parent.find('.cronStampText'),
        targetInp = parent.find('input[name=cronstamp]'),
        iMin = parent.find('.cronmin.croninp').val() || '*',
        iHr = parent.find('.cronhr.croninp').val() || '*',
        iDay = parent.find('.cronday.croninp').val() || '*',
        iMon = parent.find('.cronmonth.croninp').val() || '*',
        iWeek = parent.find('.cronweekday.croninp').val() || '*',
        iYear = parent.find('.cronyear.croninp').val() || '*',
        val = [iMin, iHr, iDay, iMon, iWeek, iYear ].join(' '),
        pretty = prettyCron.toString(val),
        resetPretty = targetText.data('resetValue');
    
    if (!resetPretty) {
        targetText.data('resetValue', targetText.text());
    }    
    targetText.text(pretty);
    targetInp.val(val);        
}

function parseCronStamps(elm, value) {
    
    var getValueFromElement = !value;
    if (!elm) {
        elm = $('.cronStampText');
    }
    if (getValueFromElement) {
        elm.each(function(){
            var eachElm = $(this), pretty;
            if (getValueFromElement) {
                value = eachElm.attr('data-cronstamp');
                pretty = parseCronStamp(value);
                eachElm.text(pretty);
            }
        });
        
    }
    
}
function parseCronStamp(value) {    
    return prettyCron.toString(value);
}
    

function switchOnInit(event, state) {
    var elm = $(this);
    elm.data('resetValue', elm.prop('checked'));
}
function switchOnChange(event, state) {    
    var elm = $(this);
    elm.prop('checked', state);    
}

function initEditTask(event) {
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel'),
        buttons = parent.find('.saveButtonsGroup, .editButtonGroup'),
        switches = parent.find('.toggleSwitch.isActive');
    toggleFormTextContent(null, parent);
    buttons.toggleClass('hidden');
    switches.bootstrapSwitch('readonly', false);
}
function initAddTask(event) {
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel');
    //toggleFormTextContent(null, parent);    
}
function initDelTask(event) {
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel');
    
}
function initShowScheduledTasks(event) {
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel');
    
}
function updateTask(event) {   
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel');
    //toggleFormTextContent(null, parent);    
}
function initCancelEditTask(event) {    
    //event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel'),
        buttons = parent.find('.saveButtonsGroup, .editButtonGroup'),
        switches = parent.find('.toggleSwitch.isActive'),
        cronText = parent.find('.cronStampText'),
        cronTextResetValue = cronText.data('resetValue'),
        switchValue = switches.data('resetValue');
    toggleFormTextContent(null, parent);    
    buttons.toggleClass('hidden');
    switches.prop('checked', switchValue);
    switches.bootstrapSwitch('state', switchValue);
    switches.bootstrapSwitch('readonly', true);
    
    if (cronTextResetValue) {
        cronText.text(cronTextResetValue);
    }
    
}

function showUIBlocker(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main =  container || $(".tasksContent "+ idClass),
        blocker = main.find('.ajaxLoading');
    blocker.css({'display': 'block'});
}
function hideUIBlocker(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main =  container || $(".tasksContent "+ idClass),
        blocker = main.find('.ajaxLoading');
    blocker.css({'display': 'none'});
}
function showFormContent(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main =  container || $(".tasksContent "+ idClass),
        toShow = main.find(".formContent");        
    toShow.removeClass('hidden');    
}
function hideFormContent(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main =  container || $(".tasksContent "+ idClass),
        toShow = main.find(".formContent");        
    toShow.addClass('hidden');     
}
function showTextContent(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main =  container || $(".tasksContent "+ idClass),
        toShow = main.find(".textContent");        
    toShow.removeClass('hidden');
}
function hideTextContent(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main =  container || $(".tasksContent "+ idClass),
        toShow = main.find(".textContent");        
    toShow.addClass('hidden');    
}
function toggleFormTextContent(id, container) {
    var idClass = id ? ('.taskPanel-'+id) : '.taskAdd',
        main = container || $(".tasksContent "+ idClass),
        forms = main.find(".formContent"),
        texts = main.find(".textContent");      
        forms.toggleClass('hidden');
        texts.toggleClass('hidden');
}