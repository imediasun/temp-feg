@extends('layouts.app')

@section('content')
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
          <h3> Permission Editor : {{ $row->module_name }} <small> Edit Permission Info </small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
		<li><a href="{{ URL::to('feg/module') }}"> Module </a></li>
        <li class="active"> Permission Editor </li>
      </ul>		  
	  
    </div>
	<div class="page-content-wrapper m-t"> 
	@include('sximo.module.tab',array('active'=>'permission','type'=>$type))

@if(Session::has('message'))
       {{ Session::get('message') }}
@endif

 {!! Form::open(array('url'=>'feg/module/savepermission/'.$module_name, 'class'=>'form-horizontal')) !!}

<div class="sbox">
	<div class="sbox-title"><h5> Module Permission </h5></div>
	<div class="sbox-content">	
	<div class="table-responsive">
		<table class="table table-striped table-bordered" id="table">
			
		<thead class="no-border">
  <tr>
	<th field="name1" width="30">No</th>
	<th field="name2" width="200">Group </th>
	<?php foreach($tasks as $item=>$val) {?>	
	<th field="name3" data-hide="phone"><?php echo $val;?> </th>
	<?php }?>

  </tr>
</thead>  
<tbody class="no-border-x no-border-y">	
  <?php $i=0; foreach($access as $gp) {?>	
  	<tr class="tr-of-roles">
		<td  width="20"><?php echo ++$i;?>
		<input type="hidden" name="group_id[]" value="<?php echo $gp['group_id'];?>" /></td>
		<td ><?php echo $gp['group_name'];?> </td>
		<?php foreach($tasks as $item=>$val) {?>	
		<td  class="">
		
		<label >
			<input name="<?php echo $item;?>[<?php echo $gp['group_id'];?>]" class="c<?php echo $gp['group_id'];?>" type="checkbox"  value="1"
			<?php if($gp[$item] ==1) echo ' checked="checked" ';?> />
		</label>	
		</td>

		<?php }?>
	</tr>  
	<?php }?>
  @foreach($users as $user)
	  <tr class="append-user" id="append-user-1">
		  <td width="20">17</td>
		  <td><select name="user_ids[]" id="" readonly=""   class="select2 userdropdown">
				  <option value="{{$user['user_id']}}">{{$user['user_name']}}</option></select></td>
          @foreach($tasks as $item=>$val)
		  <td  class="">
			  <label>
				  <input name="{{$item}}[user][{{$user['user_id']}}]" @if($user['access_data'][$item]==1)  checked @endif class="c{{$user['user_id']}}" type="checkbox"  value="{{$user['access_data'][$item]}}">
			  </label>
			  @if($item=='is_word')
				  <span onclick="deleteRow({{$user['user_id']}}, this)" style="margin-left: 10px">x</span>
				  @endif

		  </td>
		  @endforeach
	  </tr>
	  @endforeach
  </tbody>
</table>	
	</div>
		<a href="javascript:void(0)" id="add-user" class="btn btn-sm btn-primary" style="float: right; margin-top: 10px; margin-bottom: 10px">Add User</a>
		<br>
		<br>
		<div class="infobox infobox-danger fade in">
	 <button type="button" class="close" data-dismiss="alert"> x </button>
  <h5>Please Note:</h5>
  <ol> 
  	<li> If you want users <strong>only</strong> able to access their own records , then <strong>Global</strong> must be <code>uncheck</code> </li>
	<li> When you using this feature , Database table must have <strong><code>entry_by</code></strong> field </li>
	</ol>	
</div>	
<button type="submit" class="btn btn-success"> Save Changes </button>	
	
<input name="module_id" type="hidden" id="module_id" value="<?php echo $row->module_id;?>" />
</div>	</div>
 {!! Form::close() !!}	
	

</div>	</div>

<script>
	$(document).ready(function(){
	
		$(".checkAll").click(function() {
			var cblist = $(this).attr('rel');
			var cblist = $(cblist);
			if($(this).is(":checked"))
			{				
				cblist.prop("checked", !cblist.is(":checked"));
			} else {	
				cblist.removeAttr("checked");
			}	
			
		});
		var fieldCount = 2;

		$("#user_ids_1").jCombo("{{URL::to('new-location-setup/comboselect?filter=users:id:username')}}");
		var counter = $('.tr-of-roles').last().children().first().html().slice(0, 2);
	function fieldTemplate(index, counter){
		var template = '<tr class="append-user" id="append-user-'+index+'"><td width="20">'+counter+'</td>' +
				'<td><select name="user_ids[]" required id="user_ids_'+index+'"  class="select2 userdropdown"></select></td>' +
				'<td class=""><label>' +
				'<input name="is_global[user]"  type="checkbox" value="1" > </label></td> <td class=""><label><input name="is_view[user]"  type="checkbox" value="1" > ' +
				'</label> </td> ' +
				'<td class=""><label><input data-field="is_detail[user]" name="is_detail[user]"  type="checkbox" value="1" ></label></td> ' +
				'<td class=""><label><input data-field="is_add[user]" name="is_add[user]"  type="checkbox" value="1" ></label> </td> ' +
				'<td class=""><label><input data-field ="is_edit[user]" name="is_edit[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input  data-field="is_remove[user]" name="is_remove[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input data-field="is_excel[user]" name="is_excel[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input data-field="is_csv[user]" name="is_csv[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input data-field=is_print[user]" name="is_print[user]"  type="checkbox" value="1" ></ins></div></label></td>';
		template +='<td class=""><label><input data-field="is_pdf[user]" name="is_pdf[user]"  type="checkbox" value="1" ></div></label></td>'+
				'<td class=""><label><input data-field="is_word[user]" name="is_word[user]"  type="checkbox" value="1" ></div></label><span id="rmv-row" style="margin-left: 10px">x</span></td>' +
				'</tr>';

	return template;
	}
onchangeIndex = 1 ;
		$('#add-user').click(function () {
			counter++;
			var fieldtemplate = fieldTemplate(fieldCount, counter);

			$('#table').find('tbody').append(fieldtemplate);
			$('input[type="checkbox"],input[type="radio"]').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue'
			});
			$("#user_ids_"+fieldCount).select2({'width':'100%'});
			$("#user_ids_"+fieldCount).jCombo("{{URL::to('new-location-setup/comboselect?filter=users:id:first_name|last_name')}}");
			onchangeIndex = fieldCount;
			fieldCount++;
		});
 var changeDropdown =false;
$(document).on('change','.userdropdown',function(){
	var userId = $(this).val();
	console.log(onchangeIndex);
	if(userId) {
		console.log(userId);
		var selfObject = $(this);
		var checkboxes = $('#append-user-' + onchangeIndex + ' input');
		checkboxes.each(function () {
			console.log($(this).attr('name'));
			$(this).attr('name', $(this).attr('name')+'[' + userId + ']');
		})
	}
});
		$(document).on('click','#rmv-row', function () {
			$(this).closest('tr').remove();
		})

	});
	function deleteRow (id, current) {

		url = "{{URL::to('feg/module/delete-permission/new-location-setup')}}";
		$.ajax({
			type:"POST",
			 data:{
				id:id
			 },
			url:url,
			success:function (res) {
               if (res.status=200){
                    current.closest('tr').remove();
                   notyMessage("User permission removed successfully");
               }
               else{
                   notyMessageError("There was issue deleting user permission")
               }
			}

		})
	}
</script>
@stop