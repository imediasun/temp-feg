jQuery(document).ready(function ($) {
    $(document).on('click', '.cancelEditTask', initCancelEditTask);
    $(document).on('click', '.addUpdateTask', updateTask);
    $(document).on('click', '.editTask', initEditTask);
    $(document).on('click', '.deleteTask', initDelTask);
    $(document).on('click', '.showSchedules', initShowScheduledTasks);
    $(document).on('click', '.addNewTask', initAddTask);
    
    $('.toggleSwitch').bootstrapSwitch({
        onInit: switchOnInit,
        onSwitchChange: switchOnChange,
    });

});

function switchOnInit(event, state) {
    console.log(event,state, this);
}
function switchOnChange(event, state) {
    console.log(event,state, this);
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
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel'),
        buttons = parent.find('.saveButtonsGroup, .editButtonGroup'),
        switches = parent.find('.toggleSwitch.isActive');;
    toggleFormTextContent(null, parent);    
    buttons.toggleClass('hidden');
    switches.bootstrapSwitch('readonly', true);
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