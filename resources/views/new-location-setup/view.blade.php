@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger empty-popup-time" onclick="beforepopupClose(); ajaxViewClose('#{{ $pageModule }}');">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content"> 
@endif



		<table class="table table-striped table-bordered" >
			<tbody>
			<tr>
				<td width='30%' class='label-view text-right'>
					{{ SiteHelpers::activeLang('Location Name', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
				</td>
				<td>{{ $row->location_name }} </td>

			</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location ID', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
						<td>{{ $row->location_id }} </td>
						
					</tr><tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Store ID', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
						<td>{{ $row->store_id }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('TeamViewer ID', (isset($fields['teamviewer_id']['language'])? $fields['teamviewer_id']['language'] : array())) }}
						</td>
						<td>{{ $row->teamviewer_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Teamviewer Password', (isset($fields['teamviewer_passowrd']['language'])? $fields['teamviewer_passowrd']['language'] : array())) }}
						</td>
						<td><span id="tmpass">{{$row->teamviewer_passowrd }}</span><a href="javascript:void(0);" onclick="togglePasswords(this,'tmpass',false,'tmv')" style="float: right" class="btn btn-sm btn-primary">Show Password </a></td>

						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Lock Server?', (isset($fields['is_server_locked']['language'])? $fields['is_server_locked']['language'] : array())) }}
						</td>
						<td>{{ $row->is_server_locked ==1?'Yes':'No' }} </td>
						
					</tr>
					<?php if($row->is_server_locked ==1){?>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Windows User', (isset($fields['windows_user']['language'])? $fields['windows_user']['language'] : array())) }}
						</td>
						<td>{{ $row->windows_user }} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Windows Password', (isset($fields['windows_user_password']['language'])? $fields['windows_user_password']['language'] : array())) }}
						</td>
						<td><span id="wpass">{{$row->windows_user_password }}</span>@if($row->windows_user_password)<a href="javascript:void(0);" onclick="togglePasswords(this,'wpass',false,'wndows')" style="float: right" class="btn btn-sm btn-primary">Show Password </a>@endif</td>
						
					</tr>
				<?php }?>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Remote Desktop Needed?', (isset($fields['is_remote_desktop']['language'])? $fields['is_remote_desktop']['language'] : array())) }}
						</td>
						<td>{{ $row->is_remote_desktop == 1 ? 'Yes' :'No' }} </td>
						
					</tr>
			<?php if($row->is_remote_desktop == 1){?>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Computer Name', (isset($fields['rdp_computer_name']['language'])? $fields['rdp_computer_name']['language'] : array())) }}
						</td>
						<td>{{ $row->rdp_computer_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('User', (isset($fields['rdp_computer_user']['language'])? $fields['rdp_computer_user']['language'] : array())) }}
						</td>
						<td>{{ $row->rdp_computer_user }} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Password', (isset($fields['rdp_computer_password']['language'])? $fields['rdp_computer_password']['language'] : array())) }}
						</td>
						<td ><span id="rdpass">{{ $row->rdp_computer_password }}</span>@if($row->rdp_computer_password) <a href="javascript:void(0);" onclick="togglePasswords(this,'rdpass',false,'rdp')" style="float: right" class="btn btn-sm btn-primary">Show Password </a>@endif</td>

					</tr>
			<?php } ?>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('VM URL', (isset($fields['vm_url']['language'])? $fields['vm_url']['language'] : array())) }}
						</td>
						<td>{{ $row->vm_url }}</td>

					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('VM User', (isset($fields['vm_user']['language'])? $fields['vm_user']['language'] : array())) }}
						</td>
						<td>{{ $row->vm_user }}</td>

					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('VM Password', (isset($fields['vm_password']['language'])? $fields['vm_password']['language'] : array())) }}
						</td>
						<td ><span id="vmpass">{{ $row->vm_password }}</span> @if($row->vm_password) <a href="javascript:void(0);" onclick="togglePasswords(this,'vmpass',false,'vmpass')"style="float: right" class="btn btn-sm btn-primary">Show Password </a>@endif</td>
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sync Install', (isset($fields['sync_install']['language'])? $fields['sync_install']['language'] : array())) }}
						</td>
						<td>{{ $row->sync_install==1? 'Yes' : 'No' }}</td>

					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sync Time', (isset($fields['sync_time']['language'])? $fields['sync_time']['language'] : array())) }}
						</td>
						<td>{{ $row->sync_time==null ? '-' :$row->sync_time }} {{ $row->sync_time_zone }}</td>

					</tr><tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sync Difference', (isset($fields['sync_difference']['language'])? $fields['sync_difference']['language'] : array())) }}
						</td>
						<td>{{ $row->sync_difference==null? '-' :$row->sync_difference }}</td>

					</tr>

			</tbody>	
		</table>  
			
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>

	var  totalTime = '';
	var timetoclose='';
	var settimeout =  showPopups();
	var passwords  = {!! $passwords !!};


	function togglePasswords(obj,span,isEncrypted,field){
		var password = '';

		if(($.trim(passwords[field].encrypted)).length < 1){
			return false;
		}
		if(isEncrypted == true){
			password = passwords[field].encrypted;
			$(obj).text('Show Password');
			$(obj).attr('onclick','togglePasswords(this,"'+span+'",false,"'+field+'")');
		}else{
			password = passwords[field].decrypted;
			$(obj).text('Hide Password');
			$(obj).attr('onclick','togglePasswords(this,"'+span+'",true,"'+field+'")');
		}
		$('#'+span).text(password);
	}
	function showPopups()
	{

		totalTime = Number('{{env('NOTIFICATION_POPUP_SHOW_TIMEOUT_PASSWORD_VAULT',1)}}') * 60000;
		timetoclose = Number('{{env('NOTIFICATION_POPUP_CLOSE_TIMEOUT_PASSWORD_VAULT', 3)}}') * 60000;
		showFirstPopup = setTimeout(function () {
			App.notyConfirm({
				message: "<b>No Activity Detected</b><br />Are you still using this page? For security purposes this page will automatically close itself in 3 minutes.",
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				timeout:500,
				close:function(){
					beforepopupClose();
					clearTimeout(hidePopup);
					clearTimeout(showFirstPopup);
					ajaxViewClose('#{{ $pageModule }}');
				},
				confirm: function (){
                    clearTimeout(showFirstPopup);
                    clearTimeout(hidePopup);
                    showPopups();
				},
				cancel:function () {
                    clearTimeout(showFirstPopup);
                    clearTimeout(hidePopup);
                    reloadPage();

				}
			});
			hidePopup = setTimeout(function () {
				console.log("from hide popup");
				reloadPage();
			},timetoclose);

		}, totalTime);

	}
	function reloadPage() {
		var alertpopup = document.getElementById('noty_topCenter_layout_container');
		$(alertpopup).remove();
		beforepopupClose();
		clearTimeout(hidePopup);
		clearTimeout(showFirstPopup);
		ajaxViewClose('#{{ $pageModule }}');
		//$('.btn-search[data-original-title="Reload Data"]').trigger('click');
	}
</script>	