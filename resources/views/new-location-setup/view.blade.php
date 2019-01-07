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
							{{ SiteHelpers::activeLang('Vendor Id', (isset($fields['vendor_id']['language'])? $fields['vendor_id']['language'] : array())) }}
						</td>
						<td>{{ $row->vendor_id }} </td>
						
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
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Is Remote Desktop', (isset($fields['is_remote_desktop']['language'])? $fields['is_remote_desktop']['language'] : array())) }}
						</td>
						<td>{{ $row->is_remote_desktop == 1 ? 'Yes' :'No' }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Rdp Computer Name', (isset($fields['rdp_computer_name']['language'])? $fields['rdp_computer_name']['language'] : array())) }}
						</td>
						<td>{{ $row->rdp_computer_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Rdp Computer User', (isset($fields['rdp_computer_user']['language'])? $fields['rdp_computer_user']['language'] : array())) }}
						</td>
						<td>{{ $row->rdp_computer_user }} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('Rdp Computer Passowrd', (isset($fields['rdp_computer_password']['language'])? $fields['rdp_computer_password']['language'] : array())) }}
						</td>
						<td ><span id="rdpass">{{ $row->rdp_computer_password }}</span> <a href="javascript:void(0);" id="rpbtn" style="float: right" class="btn btn-sm btn-primary">Show Password </a></td>

					</tr>

			</tbody>	
		</table>  
			
		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script>
	var passwords  = {
		rdp:{
			encrypted:'{{ $row->rdp_computer_password }}',
			decrypted:'{{ SiteHelpers::decryptStringOPENSSL($row->rdp_computer_password) }}',
		},
		tmv:{
			encrypted:'{{ $row->teamviewer_passowrd }}',
			decrypted:'{{ SiteHelpers::decryptStringOPENSSL($row->teamviewer_passowrd )}}',
		},
		wndows:{
			encrypted:'{{ $row->windows_user_password }}',
			decrypted:'{{ SiteHelpers::decryptStringOPENSSL($row->windows_user_password) }}',
		}
	};

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

</script>	