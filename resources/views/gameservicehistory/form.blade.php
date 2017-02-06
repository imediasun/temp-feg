
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'gameservicehistory/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal', 'parsley-validate'=>'','novalidate'=>' ','id'=> 'gameservicehistoryFormAjax')) !!}
			<div class="col-md-12">
                <fieldset><legend> Game Service History</legend>
                {!! Form::hidden('id', $row['id']) !!}
                    
				  <div class="form-group  " >
					<label for="Game Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Game (Asset ID - Game Title)', (isset($fields['game_id']['language'])? $fields['game_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='game_id'  id='game_name' class='select2' required></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="location_id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Location ', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <select name='location_id'  id='location_id' class='select2'></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Down" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Down', (isset($fields['date_down']['language'])? $fields['date_down']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <input name='date_down' type="text" rows='5' id='date_down' class='form-control date' 
                             value="{{ DateHelpers::formatDate($row['date_down']) }}" 
                             required>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Problem" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Problem', (isset($fields['problem']['language'])? $fields['problem']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='problem' rows='5' id='problem' class='form-control '
				           required>{{ $row['problem'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Down User Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Down User ', (isset($fields['down_user_id']['language'])? $fields['down_user_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <select name="down_user_id" id="down_user_id" class="select2"></select>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Solution" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Solution', (isset($fields['solution']['language'])? $fields['solution']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <textarea name='solution' rows='5' id='solution' class='form-control '
				           required>{{ $row['solution'] }}</textarea>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Date Up" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Date Up', (isset($fields['date_up']['language'])? $fields['date_up']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
					  <input type="text" name='date_up'  id='date_up' class='form-control date' 
                             value="{{ DateHelpers::formatDate($row['date_up']) }}"
                             required>
					 </div>
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
				  <div class="form-group  " >
					<label for="Up User Id" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang('Up User', (isset($fields['up_user_id']['language'])? $fields['up_user_id']['language'] : array())) !!}
					</label>
					<div class="col-md-6">
                        <select class="select2" name="up_user_id" id="up_user_id"></select>

					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 

				   </fieldset>
			</div>


            <div style="clear:both"></div>

            <div class="form-group">
                <label class="col-sm-4 text-right">&nbsp;</label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


@if($setting['form-method'] =='native')
    </div>

@endif


</div>

<script type="text/javascript">
$(document).ready(function() {
    
    $("#location_id").jCombo("{{ URL::to('gameservicehistory/comboselect?filter=location:id:id|location_name') }}" + "&delimiter=%20|%20",
            {  selected_value : "{{ $row['location_id'] }}", initial_text:'-- Select Location --' });
                
    $("#up_user_id").jCombo("{{ URL::to('gameservicehistory/comboselect?filter=users:id:username') }}",
            {  selected_value : '{{ $row["up_user_id"] }}',initial_text:'-- Select User --' });
    $("#down_user_id").jCombo("{{ URL::to('gameservicehistory/comboselect?filter=users:id:username') }}",
            {  selected_value : '{{ $row["down_user_id"] }}',initial_text:'-- Select User --' });
    $("#game_name").jCombo("{{ URL::to('gameservicehistory/comboselect?filter=game:id:id|game_name') }}" + "&delimiter= - ",
            {  selected_value : '{{ $row["game_id"] }}',initial_text:'-- Select Game --' });
    $('.addC').relCopy({});
    $('.editor').summernote();
    $('.previewImage').fancybox();
    $('.tips').tooltip();
    $(".select2").select2({ width:"98%"});
    $('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
    $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
    $('input[type="checkbox"],input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    
    });
    
    $('.removeCurrentFiles').on('click',function(){
        var removeUrl = $(this).attr('href');
        $.get(removeUrl,function(response){});
        $(this).parent('div').empty();
        return false;
    });
    var form = $('#gameservicehistoryFormAjax');
    form.parsley();
    form.submit(function(){

        if(form.parsley('isValid') == true){
            var options = {
                dataType:      'json',
                beforeSubmit :  showRequest,
                success:       showResponse
            }
            $(this).ajaxSubmit(options);
            return false;

        } else {
            return false;
        }

    });

});

function showRequest()
{
    $('.ajaxLoading').show();
}
function showResponse(data)  {

    if(data.status == 'success')
    {
        ajaxViewClose('#{{ $pageModule }}');
        ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
        notyMessage(data.message);
        $('#sximo-modal').modal('hide');
    } else {
        notyMessageError(data.message);
        $('.ajaxLoading').hide();
        return false;
    }
}			 

</script>		 