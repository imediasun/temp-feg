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

 {!! Form::open(array('url'=>'feg/module/savepermission/'.$module_name, 'class'=>'form-horizontal', 'id'=>'permissionform')) !!}
        <style>
            .emptyRow{
                border: none !important;
                line-height: 30px  !important;
                background-color: white !important;
            }
        </style>
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
  	<tr class="tr-of-roles tr-of-roles-first-group">
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
  <tr><td class="emptyRow">&nbsp;</td><td class="emptyRow"><h3><b>Users Permissions</b></h3></td></tr>
  @foreach($users as $user)
	  <tr class="append-user tr-of-roles" id="append-user-1">
		  <td width="20">{{++$i}}</td>
		  <input type="hidden" name="user_ids[]" value="{{$user['user_id']}}">
		  <td>{{$user['user_name']}}</td>
          @foreach($tasks as $item=>$val)
		  <td  class="">
			  <label>
				  <input name="{{$item}}[user][{{$user['user_id']}}]" @if($user['access_data'][$item]==1)  checked @endif class="c{{$user['user_id']}}" type="checkbox"  value="{{$user['access_data'][$item]}}">
			  </label>
			  @if($item=='is_word')
				  <span onclick="deleteRow({{$user['user_id']}}, this)" style="margin-left: 10px ;cursor: pointer">x</span>
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
        var form = $('#permissionform');


		var fieldCount = 2;

		$("#user_ids_1").jCombo("{{URL::to('new-location-setup/comboselect?filter=users:id:username')}}");
		// var counter = $('.tr-of-roles').last().children().first().html().slice(0, 2);
		var counter = 17;
	function fieldTemplate(index, counter){
		var template = '<tr class="append-user tr-of-roles" id="append-user-'+index+'"><td width="20">'+counter+'</td>' +
				'<td><select name="user_ids[]" required id="user_ids_'+index+'"  class="select2 userdropdown"></select></td>' +
				'<td class=""><label>' +
				'<input  data-field="is_global[user]" name="is_global[user]"  type="checkbox" value="1" > </label></td> <td class=""><label>' +
				'<input data-field="is_view[user]" name="is_view[user]"  type="checkbox" value="1" > ' +
				'</label> </td> ' +
				'<td class=""><label><input data-field="is_detail[user]" name="is_detail[user]"  type="checkbox" value="1" ></label></td> ' +
				'<td class=""><label><input data-field="is_add[user]" name="is_add[user]"  type="checkbox" value="1" ></label> </td> ' +
				'<td class=""><label><input data-field ="is_edit[user]" name="is_edit[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input  data-field="is_remove[user]" name="is_remove[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input data-field="is_excel[user]" name="is_excel[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input data-field="is_csv[user]" name="is_csv[user]"  type="checkbox" value="1" ></div></label></td> ' +
				'<td class=""><label><input data-field="is_print[user]" name="is_print[user]"  type="checkbox" value="1" ></ins></div></label></td>';
		template +='<td class=""><label><input data-field="is_pdf[user]" name="is_pdf[user]"  type="checkbox" value="1" ></div></label></td>'+
				'<td class=""><label><input data-field="is_word[user]" name="is_word[user]"  type="checkbox" value="1" ></div></label><span id="rmv-row" onclick="deleteRow(0, this, true)" style="margin-left: 10px">x</span></td>' +
				'</tr>';

	return template;
	}
onchangeIndex = 1 ;
		$('#add-user').click(function () {
            counter = $('.tr-of-roles').last().children().first().html().slice(0, 3);
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
		var selfObject = $(this);
		var checkboxes = $('#append-user-' + onchangeIndex + ' input');
		checkboxes.each(function () {
			var ddd = $(this).attr('data-field');
			console.log(ddd);
			$(this).attr('name', ddd+'[' + userId + ']');
		})
	}
});
		// $(document).on('click','#rmv-row', function () {
		// 	$(this).closest('tr').remove();
		// })

	});
	function reindexing(current) {
        current.closest('tr').remove();
        indexOfDeletedRow = $('.tr-of-roles-first-group').last().children().first().html().slice(0, 3);
        $('.append-user').each(function(index){
            ++indexOfDeletedRow;
            $(this).children().first().html(indexOfDeletedRow);
        });
    }
	function deleteRow (id, current, removeNewlyCreatedPermissionRow = false) {

	    var deleteConfirmation = removeNewlyCreatedPermissionRow == false ? confirm('Do you really want to delete this permission?') : removeNewlyCreatedPermissionRow;

		if(deleteConfirmation){
            if(removeNewlyCreatedPermissionRow == true){
                reindexing(current);
            }else{
                url = "{{URL::to('feg/module/delete-permission/new-location-setup')}}";
                $.ajax({
                    type:"POST",
                    data:{
                        id:id
                    },
                    url:url,
                    success:function (res) {
                        if (res.status=200){
                            notyMessage("User permission removed successfully");
                            reindexing(current);
                        }
                        else{
                            notyMessageError("There was issue deleting user permission")
                        }
                    }

                })
            }
		}
	}
</script>
@stop