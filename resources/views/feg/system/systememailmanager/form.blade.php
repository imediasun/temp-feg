@if($setting['form-method'] =='native')
<div class="sbox">
    <div class="sbox-title">  
        <h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
            <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
        </h4>
    </div>

    <div class="sbox-content"> 
@endif	
        {!! Form::open(array('url'=>'feg/system/systememailreportmanager/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => false , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'systemreportsemailmanagerFormAjax')) !!}
        <div class="col-md-12">
            <fieldset><legend>System Email Manager - @if($isEdit) Edit @else Add @endif Emails</legend>
            {!! Form::hidden('id', $row['id']) !!}
            <div class="panel white-bg">
                 <div class="panel-heading">
                     <strong>Configurations</strong>
                 </div>
                 <div class="panel-body">
                    <div class="form-group  " >
                      <label for="Configuration Name" class=" control-label col-md-4 text-left">
                      {!! SiteHelpers::activeLang('Configuration Name', (isset($fields['report_name']['language'])? $fields['report_name']['language'] : array())) !!}
                      </label>
                      <div class="col-md-6">
                        {!! Form::text('report_name', $row['report_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                       </div> 
                       <div class="col-md-2">

                       </div>
                    </div>                          
                    <div class="form-group  " >
                      <label for="Is Active" class=" control-label col-md-4 text-left">
                      {!! SiteHelpers::activeLang('Is Active', (isset($fields['is_active']['language'])? $fields['is_active']['language'] : array())) !!}
                      </label>
                      <div class="col-md-6">
                        {!! Form::checkbox('is_active', $row['is_active'], $row['is_active']=="1") !!}
                       </div> 
                       <div class="col-md-2">

                       </div>
                    </div>  
                    <div class="form-group  " >
                      <label for="" class=" control-label col-md-4 text-left">
                          Filter emails based on location?
                      </label>
                      <div class="col-md-6">
                        {!! Form::checkbox('has_locationwise_filter', $row['has_locationwise_filter'], $row['has_locationwise_filter']=="1") !!}
                       </div> 
                       <div class="col-md-2">

                       </div>
                    </div>                      
                 </div>
                 <div class="panel-footer">
                 </div>
            </div>
            <table class="table table-striped table-bordered" id="table">
                <thead class="no-border">
                    <tr>
                        <th width="8%">Description</th>
                        <th width="23%">Groups / Managers / Contacts</th>
                        <th width="23%">Users</th>
                        <th width="23%">Include (comma separated)</th>
                        <th width="23%">Exclude (comma separated)</th>
                    </tr>
                </thead>
                <tbody class="no-border-x no-border-y">
                    <tr>
                        <td>TO</td>
                        <td>
                            User Groups:<br/>
                            <select multiple name='to_email_groups[]' 
                                    id="to_email_groups" 
                                    class='select2'></select><br/> <br/>
                            Location based Roles:<br/>
                            <div class="clearfix">
                            <input name='to_email_location_contacts' 
                                   id="to_email_location_contacts"  
                                   type="text" 
                                   class="form-control autoheight nopadding"/>                            
                            </div>                            
                        </td>
                        <td><select multiple name='to_email_individuals[]' 
                                    id="to_email_individuals" 
                                    class='select2'></select></td>
                        <td><textarea name='to_include_emails' 
                                      id="to_include_emails"  
                                      class="form-control" >{!! $row['to_include_emails'] !!}</textarea></td>
                        <td><textarea name='to_exclude_emails' 
                                      id="to_exclude_emails"  
                                      class="form-control"  >{!! $row['to_exclude_emails'] !!}</textarea></td> 
                    </tr>
                    <tr>
                        <td>CC</td>
                        <td>
                            User Groups:<br/>
                            <select multiple name='cc_email_groups[]' 
                                    id="cc_email_groups" 
                                    class='select2'></select>
                            <br/> <br/>
                            Location Contact and Managers:<br/>
                            <div class="clearfix">
                            <input name='cc_email_location_contacts' 
                                   id="cc_email_location_contacts"  
                                   type="text" 
                                   class="form-control autoheight nopadding"/>                            
                            </div>
                        </td>
                        <td><select multiple name='cc_email_individuals[]' 
                                    id="cc_email_individuals" 
                                    class='select2'></select></td>
                        <td><textarea name='cc_include_emails' 
                                      id="cc_include_emails"  
                                      class="form-control" >{!! $row['cc_include_emails'] !!}</textarea></td>
                        <td><textarea name='cc_exclude_emails' 
                                      id="cc_exclude_emails"  
                                      class="form-control"  >{!! $row['cc_exclude_emails'] !!}</textarea></td>                   
                    </tr>
                    <tr>
                        <td>BCC</td>
                        <td>
                            User Groups:<br/>
                            <select multiple name='bcc_email_groups[]' 
                                    id="bcc_email_groups" 
                                    class='select2'></select>
                            <br/> <br/>
                            Location Contact and Managers:<br/>
                            <div class="clearfix">
                            <input name='bcc_email_location_contacts' 
                                   id="bcc_email_location_contacts"  
                                   type="text" 
                                   class="form-control autoheight nopadding"/>
                            </div>
                        </td>
                        <td><select multiple name='bcc_email_individuals[]' 
                                    id="bcc_email_individuals" 
                                    class='select2'></select></td>
                        <td><textarea name='bcc_include_emails' 
                                      id="bcc_include_emails"  
                                      class="form-control" >{!! $row['bcc_include_emails'] !!}</textarea></td>
                        <td><textarea name='bcc_exclude_emails' 
                                      id="bcc_exclude_emails"  
                                      class="form-control"  >{!! $row['bcc_exclude_emails'] !!}</textarea></td>
                    </tr>
                </tbody>
            </table>            
            <div class="panel white-bg">
                 <div class="panel-heading">
                     <strong>Email recipients while testing</strong>
                 </div>
                 <div class="panel-body"> 
              <div class="form-group  " >
                <label for="Test Email" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('TO (comma separated)', (isset($fields['test_email']['language'])? $fields['test_email']['language'] : array())) !!}
                </label>
                <div class="col-md-6">
                  {!! Form::text('test_to_emails', $row['test_to_emails'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                 </div> 
                 <div class="col-md-2">

                 </div>
              </div> 
              <div class="form-group  " >
                <label for="Test Email Cc" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('CC (comma separated)', (isset($fields['test_email_cc']['language'])? $fields['test_email_cc']['language'] : array())) !!}
                </label>
                <div class="col-md-6">
                  {!! Form::text('test_cc_emails', $row['test_cc_emails'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                 </div> 
                 <div class="col-md-2">

                 </div>
              </div> 
              <div class="form-group  " >
                <label for="Test Email Bcc" class=" control-label col-md-4 text-left">
                {!! SiteHelpers::activeLang('BCC (comma separated)', (isset($fields['test_email_bcc']['language'])? $fields['test_email_bcc']['language'] : array())) !!}
                </label>
                <div class="col-md-6">
                  {!! Form::text('test_bcc_emails', $row['test_bcc_emails'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                 </div> 
                 <div class="col-md-2">

                 </div>
              </div> 
                </div>
                 <div class="panel-footer">

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
</div>	
@endif	

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
<?php

    $locationContactNamesForSelect = array();
    foreach($locationContactNames as $key => $value) {
        $locationContactNamesForSelect[] = array("id" => $key, "text" => $value);
    }

?>	 
	
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
    renderDropdown($(".select2 "), { width:"100%"});
	$('.date').datepicker({format:'mm/dd/yyyy',autoclose:true})
	$('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
	}).on('ifCreated', function(event){        
        $(this).attr('value', $(this).prop('checked')*1);
	}).on('ifChanged', function(event){        
        $(this).attr('value', $(this).prop('checked')*1);
    });
   
   	var groupListUrl = "{{ URL::to('feg/system/systememailreportmanager/comboselect?filter=tb_groups:group_id:name') }}"
        ,userListUrl = "{{ URL::to('feg/system/systememailreportmanager/comboselect?filter=users:id:first_name|last_name') }}"
        ,locationContactSelectData = <?php echo json_encode($locationContactNamesForSelect); ?>;
        
    $("#to_email_groups").jCombo(groupListUrl,
                {selected_value: '{{  $row["to_email_groups"] }}'});
    $("#to_email_individuals").jCombo(userListUrl,
                {  selected_value : '{{ $row["to_email_individuals"] }}' });
    $("#cc_email_groups").jCombo(groupListUrl,
                {selected_value: '{{  $row["cc_email_groups"] }}'});
    $("#cc_email_individuals").jCombo(userListUrl,
                {  selected_value : '{{ $row["cc_email_individuals"] }}' });
    $("#bcc_email_groups").jCombo(groupListUrl,
                {selected_value: '{{  $row["bcc_email_groups"] }}'});                     
    $("#bcc_email_individuals").jCombo(userListUrl,
                {  selected_value : '{{ $row["bcc_email_individuals"] }}' });


    renderDropdown($("#to_email_location_contacts"), {
        data: locationContactSelectData,
        multiple: true
    });
    $("#to_email_location_contacts").select2('val',
            '{{ $row["to_email_location_contacts"] }}'.split(','));
    
    renderDropdown($("#cc_email_location_contacts"), {
        data: locationContactSelectData,
        multiple: true
    });
    $("#cc_email_location_contacts").select2('val',
        '{{ $row["cc_email_location_contacts"] }}'.split(','));
    
    renderDropdown($("#bcc_email_location_contacts"), {
        data: locationContactSelectData,
        multiple: true
    });
    $("#bcc_email_location_contacts").select2('val',
        '{{ $row["bcc_email_location_contacts"] }}'.split(','));
    
        
	var form = $('#systemreportsemailmanagerFormAjax'); 
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