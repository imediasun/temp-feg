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
</div>
<script type="text/javascript">
    var tasksList = [];
    @if (!empty($rowData)) 
        tasksList = <?php echo json_encode($rowData); ?>;
    @endif    
</script>
