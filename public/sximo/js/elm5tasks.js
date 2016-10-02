jQuery(document).ready(function ($) {
    $(document).on('click', '.cancelEditTask', initCancelEditTask);
    $(document).on('click', '.addUpdateTask', updateTask);
    $(document).on('click', '.editTask', initEditTask);
    $(document).on('click', '.deleteTask', initDelTask);
    $(document).on('click', '.showSchedules', initShowScheduledTasks);
    $(document).on('click', '.addNewTask', initAddTask);

});
function initEditTask(event) {
    event.preventDefault();
    var btn = $(this),
        parent = btn.closest('.taskPanel');
    toggleFormTextContent(null, parent);
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
        parent = btn.closest('.taskPanel');
    toggleFormTextContent(null, parent);    
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
        texts.toggleClass('texts');
}