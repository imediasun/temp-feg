@if($setting['view-method'] =='native')
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
						<td>{{ $row->id }} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Location Id', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) }}
						</td>
						<td>{{ $row->location_id }} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Teamviewer Id', (isset($fields['teamviewer_id']['language'])? $fields['teamviewer_id']['language'] : array())) }}
						</td>
						<td>{{ $row->teamviewer_id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Teamviewer Passowrd', (isset($fields['teamviewer_passowrd']['language'])? $fields['teamviewer_passowrd']['language'] : array())) }}
						</td>
						<td><span id="tmpass">{{$row->teamviewer_passowrd }}</span><a href="javascript:void(0);" id="tpass" style="float: right" class="btn btn-sm btn-primary">Show Password </a></td>

						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Server Locked', (isset($fields['is_server_locked']['language'])? $fields['is_server_locked']['language'] : array())) }}
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
							{{ SiteHelpers::activeLang('Windows User Password', (isset($fields['windows_user_password']['language'])? $fields['windows_user_password']['language'] : array())) }}
						</td>
						<td><span id="wpass">{{$row->windows_user_password }}</span><a href="javascript:void(0);" id="wpbtn" style="float: right" class="btn btn-sm btn-primary">Show Password </a> </td>
						
					</tr>
				<?php }?>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Remote Desktop', (isset($fields['is_remote_desktop']['language'])? $fields['is_remote_desktop']['language'] : array())) }}
						</td>
						<td>{{ $row->is_remote_desktop == 1 ? 'Yes' :'No' }} </td>
						
					</tr>
			<?php if($row->is_remote_desktop == 1){?>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('RDP Computer Name', (isset($fields['rdp_computer_name']['language'])? $fields['rdp_computer_name']['language'] : array())) }}
						</td>
						<td>{{ $row->rdp_computer_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('RDP Computer User', (isset($fields['rdp_computer_user']['language'])? $fields['rdp_computer_user']['language'] : array())) }}
						</td>
						<td>{{ $row->rdp_computer_user }} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('RDP Computer Passowrd', (isset($fields['rdp_computer_password']['language'])? $fields['rdp_computer_password']['language'] : array())) }}
						</td>
						<td ><span id="rdpass">{{ $row->rdp_computer_password }}</span> <a href="javascript:void(0);" id="rpbtn" style="float: right" class="btn btn-sm btn-primary">Show Password </a></td>

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
						<td>{{ $row->vm_password }}</td>

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
						<td>{{ $row->sync_time }} {{ $row->sync_time_zone }}</td>

					</tr><tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Sync Difference', (isset($fields['sync_difference']['language'])? $fields['sync_difference']['language'] : array())) }}
						</td>
						<td>{{ $row->sync_difference }}</td>

					</tr>

			</tbody>	
		</table>  
			
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
	var settimeout =  showPopups();
	var passwords  = {!! $passwords !!};

	 $(document).on('click','#tpass',(function () {
	 $('#tmpass').text(passwords.tmv.decrypted);
	 $(this).text('Hide Password');
	 $(this).attr("id", "hideid");

		 }
	 ));
	$(document).on('click','#hideid',(function () {
				$('#tmpass').text(passwords.tmv.encrypted);
				$(this).text('Show Password');
				$(this).attr("id", "tpass");

	}));


	$(document).on('click','#wpbtn',(function () {
	 $('#wpass').text(passwords.wndows.decrypted);
	 $(this).text('Hide Password');
	 $(this).attr("id", "hidewpid");

		 }
	 ));
	$(document).on('click','#hidewpid',(function () {
				$('#wpass').text(passwords.wndows.encrypted);
				$(this).text('Show Password');
				$(this).attr("id", "wpbtn");

	})
	)
	$(document).on('click','#rpbtn',(function () {
	 $('#rdpass').text(passwords.rdp.decrypted);
	 $(this).text('Hide Password');
	 $(this).attr("id", "hiderd");

		 }
	 ));
	$(document).on('click','#hiderd',(function () {
				$('#rdpass').text(passwords.rdp.encrypted);
				$(this).text('Show Password');
				$(this).attr("id", "rpbtn");

	})
	);
	function showPopups()
	{
		var totalTime = {{env('NOTIFICATION_POPUP_SHOW_TIMEOUT_PASSWORD_VAULT',1)}} * 60000;

		showFirstPopup = setTimeout(function () {
			App.notyConfirm({
				message: "Do you need more time to view or you want to cancel",
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				timeout:6000,
				confirm: function (){
					clearTimeout(hidePopup);
					reloadPage();
				},
				cancel:function () {
					totalTime += 120000;
					showFirstPopup =  showPopups();
				}
			});
			hidePopup = setTimeout(function () {
				reloadPage();
			},({{env('NOTIFICATION_POPUP_CLOSE_TIMEOUT_PASSWORD_VAULT')}} * 60000))

		}, totalTime);
		return showFirstPopup;
	}
	function reloadPage() {
		window.location.reload();
	}
</script>	