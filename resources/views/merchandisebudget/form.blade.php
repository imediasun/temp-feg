
@if($setting['form-method'] =='native')
    <div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'merchandisebudget/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'merchandisebudgetFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> Merchandise Budget</legend>
				  <div class="form-group  " > 
					<label for="Location Id" class=" control-label col-md-4 text-left"> 
					{!! SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}	
					</label>
					<div class="col-md-4">
                        <select name="location_id" id="location_id" class="select3" required/>
					 </div>
					 <div class="col-md-4">
					 	
					 </div>
				  </div> 					
				  <div class="form-group  " > 
					<label for="Budget Date" class=" control-label col-md-4 text-left"> 
					Budget Year
							</label>
                        <div class="col-md-4">
                                <?php
                                $years=array(2012,2013,2014,2015,2016,2017,2018,2019,2020);
                                ?>
                                <select name="budget_year" id="budget_year" class="form-control" style="width:316px">
                                    <option selected disabled>         ----- Select Year ----- </option>
                                    @foreach($years as $year)
                                        <option @if($year==$row['budget_year']) selected @endif value="{{ $year}}">{{ $year }}</option>
                                    @endforeach
                                </select>
                        </div>
					 <div class="col-md-4">
					 	
					 </div>
				  </div>
                            <fieldset>
                                <legend> Merchandise Budget Values</legend>
				  <div class="form-group  " > 
					<label for="jan" class=" control-label col-md-4 text-left">January</label>
					<div class="col-md-4">
					<input type="number" name="jan" id="jan" value="{{$row['Jan']}}" class="form-control"/>
                    </div>
					 <div class="col-md-4">
					 </div>
				  </div>
                  <div class="form-group  " >
                                    <label for="feb" class=" control-label col-md-4 text-left"> February</label>
                                    <div class="col-md-4">
                                        <input type="number" name="feb" id="feb" value="{{ $row['Feb']}}" class="form-control"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="march" class=" control-label col-md-4 text-left">
                                    March
                                    </label>
                                    <div class="col-md-4">
                                        <input type="number" name="march" id="march" value="{{$row['March']}}" class="form-control"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="april" class=" control-label col-md-4 text-left">
                                        April   </label>
                                    <div class="col-md-4">
                                        <input type="number" name="april" id="april" class="form-control" value="{{$row['April']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="may" class=" control-label col-md-4 text-left">
                                       May     </label>
                                    <div class="col-md-4">
                                        <input type="number" name="may" id="may" class="form-control" value="{{$row['May']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="jun" class=" control-label col-md-4 text-left">
                                        June</label>
                                    <div class="col-md-4">
                                        <input type="number" name="jun" id="jun" class="form-control" value="{{$row['June']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="jul" class=" control-label col-md-4 text-left">
                                     July  </label>
                                    <div class="col-md-4">
                                        <input type="number" name="jul" id="jul" class="form-control" value="{{$row['July']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="aug" class=" control-label col-md-4 text-left">
                                       August </label>
                                    <div class="col-md-4">
                                        <input type="number" name="aug" id="aug" class="form-control" value="{{$row['August']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="sep" class=" control-label col-md-4 text-left">
                                     September  </label>
                                    <div class="col-md-4">
                                        <input type="number" name="sep" id="sep" class="form-control" value="{{$row['September']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="oct" class=" control-label col-md-4 text-left">
                                       Octuber    </label>
                                    <div class="col-md-4">
                                        <input type="number" name="oct" id="oct" class="form-control" value="{{$row['Octuber']}}"/>
                                        </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="nov" class=" control-label col-md-4 text-left">
                                   November   </label>
                                    <div class="col-md-4">
                                        <input type="number" name="nov" id="nov" class="form-control" value="{{$row['November']}}"/>
                                      </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="form-group  " >
                                    <label for="dec" class=" control-label col-md-4 text-left">
                                       December </label>
                                    <div class="col-md-4">
                                        <input type="number" name="dec" id="dec" class="form-control" value="{{$row['December']}}"/>  </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                            </fieldset>
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
</div>	
@endif	

	

			 
<script type="text/javascript">
$(document).ready(function() {

    $("#location_id").jCombo("{{ URL::to('merchandisebudget/comboselect?filter=location:id:id|location_name') }}",
            {  selected_value : '{{ $row['location_id'] }}' });

	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select3").select2({ width:"98%"});
	$('.datee').datepicker({
        format: " yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years",
        changeMonth:false
    });

	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});			
	$('.removeCurrentFiles').on('click',function(){
		var removeUrl = $(this).attr('href');
		$.get(removeUrl,function(response){});
		$(this).parent('div').empty();	
		return false;
	});			
	var form = $('#merchandisebudgetFormAjax'); 
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