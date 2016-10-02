jQuery(document).ready(function ($) {
    $(document).on('click', '.cancelEditTask', function(event){});
    $(document).on('click', '.addUpdateTask', function(event){});
    $(document).on('click', '.editTask', function(event){});
    $(document).on('click', '.deleteTask', function(event){});
    $(document).on('click', '.showSchedules', function(event){});
    $(document).on('click', '.addNewTask', function(event){});

});
function initEditTask() {
    
}
function initAddTask() {
    
}
function initDelTask() {
    
}
function initShowScheduledTasks() {
    
}
function updateTask() {
    
}

function showFormContent() {
    var main = $(".tasksContent"),
        toShow = main.find(".formContent");        
    toShow.removeClass('hidden');    
}
function hideFormContent() {
    var main = $(".tasksContent"),
        toShow = main.find(".formContent");        
    toShow.addClass('hidden');     
}
function showTextContent() {
    var main = $(".tasksContent"),
        toShow = main.find(".textContent");        
    toShow.removeClass('hidden');
}
function hideTextContent() {
    var main = $(".tasksContent"),
        toShow = main.find(".textContent");        
    toShow.addClass('hidden');    
}
function toggleFormTextContent() {
    var main = $(".tasksContent"),
        forms = main.find(".formContent"),
        texts = main.find(".textContent");      
        forms.toggleClass('hidden');
        texts.toggleClass('texts');
}