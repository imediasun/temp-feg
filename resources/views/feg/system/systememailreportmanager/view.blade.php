@if($setting['view-method'] =='native')
<?php 
    $vrow = new \StdClass;
    foreach ($row as $fieldName => $value) {
        $tempValue = array();
        if (strpos($fieldName, "email_groups") > 0) {
            $values = explode(",", $value);
            foreach($values as $val) {
                if (!empty($userGroups[$val])) {
                    $tempValue[] = "<div>".
                            $userGroups[$val]->group_name.
                        "";
                }                                    
            }
            $value = implode(",</div>", $tempValue);
            if (!empty($value)) {
                $value .= "</div>";
            }                
        }
        if (strpos($fieldName, "email_individuals") > 0) {
            $values = explode(",", $value);
            foreach($values as $val) {
                if (!empty($users[$val])) {
                    $tempValue[] = "<div>".
                            $users[$val]->first_name . 
                            ' ' . $users[$val]->last_name . 
                            "<br/>(".$users[$val]->email.")";

                }                                    
            }
            $value = implode(",</div>", $tempValue);
            if (!empty($value)) {
                $value .= "</div>";
            }            
        }
        
        if (strpos($fieldName, "email_location_contacts") > 0) {
            $values = explode(",", $value);
            foreach($values as $val) {
                if (!empty($locationContactNames[$val])) {
                    $tempValue[] = "<span class='tips'>".
                            $locationContactNames[$val];
                }                                    
            }
            $value = implode(", </span>", $tempValue);
            if (!empty($value)) {
                $value .= "</span>";
            }                                                
        }     
    
        if (strpos($fieldName, "updated_at") === 0) {
            $time = strtotime($value);
            if ($time === false || $time <= 0) {
                $value = $row->created_at;
            }
        }
        
        $vrow->$fieldName = $value;
    }
?>
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content"> 
@endif	

		<table class="table table-striped table-bordered" >
			<tbody>	
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}
						</td>
						<td>{{ $vrow->id }} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Report Name', (isset($fields['report_name']['language'])? $fields['report_name']['language'] : array())) }}
						</td>
						<td>{{ $vrow->report_name }} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Active', (isset($fields['is_active']['language'])? $fields['is_active']['language'] : array())) }}
						</td>
						<td>{{ $vrow->is_active == 1 ? "Yes" : "No" }} </td>						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							Locationwise filter?
						</td>
						<td>{{ $vrow->has_locationwise_filter == 1 ? "Yes" : "No" }} </td>						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							To Email Groups
						</td>
						<td>{!! $vrow->to_email_groups !!} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							To Location contacts/managers
						</td>
						<td>{!! $vrow->to_email_location_contacts !!} </td>						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							To Email Users
						</td>
						<td>{!! $vrow->to_email_individuals !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Include Emails in TO
						</td>
						<td>{!! $vrow->to_include_emails !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Exclude Emails in TO
						</td>
						<td>{!! $vrow->to_exclude_emails !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							CC Email Groups
						</td>
						<td>{!! $vrow->cc_email_groups !!} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							CC Location contacts/managers
						</td>
						<td>{!! $vrow->cc_email_location_contacts !!} </td>						
					</tr>                    
					<tr>
						<td width='30%' class='label-view text-right'>
							CC Email Users
						</td>
						<td>{!! $vrow->cc_email_individuals !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Include Emails in CC
						</td>
						<td>{!! $vrow->cc_include_emails !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Exclude Emails in TO
						</td>
						<td>{!! $vrow->cc_exclude_emails !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							BCC Email Groups
						</td>
						<td>{!! $vrow->bcc_email_groups !!} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							BCC Location contacts/managers
						</td>
						<td>{!! $vrow->bcc_email_location_contacts !!} </td>						
					</tr>                    
					<tr>
						<td width='30%' class='label-view text-right'>
							BCC Email Users
						</td>
						<td>{!! $vrow->bcc_email_individuals !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Include Emails in BCC
						</td>
						<td>{!! $vrow->bcc_include_emails !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Exclude Emails in BCC
						</td>
						<td>{!! $vrow->bcc_exclude_emails !!} </td>
						
					</tr>
                    
					<tr>
						<td width='30%' class='label-view text-right'>
							Test Email To
						</td>
						<td>{!! $vrow->test_to_emails !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Test Email Cc
						</td>
						<td>{!! $vrow->test_cc_emails !!} </td>
						
					</tr>				
					<tr>
						<td width='30%' class='label-view text-right'>
							Test Email Bcc
						</td>
						<td>{!! $vrow->test_bcc_emails !!} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							Updated At
						</td>
						<td>{!! $vrow->updated_at !!} </td>
						
					</tr>
				
			</tbody>	
		</table>  
			
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
$(document).ready(function(){

});
</script>	