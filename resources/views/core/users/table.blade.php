<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
    <div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> {{ $pageTitle }} <small>{{ $pageNote }}</small></h5>
    <div class="sbox-tools" >
    {{--*/ $sortParam = is_null(Input::get('sort'))?'':'&sort='.Input::get('sort') /*--}}
    {{--*/ $orderParam = is_null(Input::get('order'))?'':'&order='.Input::get('order') /*--}}
    {{--*/ $rowsParam = is_null(Input::get('rows'))?'':'&rows='.Input::get('rows') /*--}}
    @if(isset($_GET['search']))
     <a href="{{ url() }}/core/users?{{ $sortParam }}{{ $orderParam }}{{ $rowsParam }}"
        class="btn btn-xs btn-white tips btn-search" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
    @endif
    @if(Session::get('gid') ==10)
        <a href="{{ URL::to('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
    @endif
    </div>
	</div>
	<div class="sbox-content" >
        @if($setting['usesimplesearch']!='false')
            <?php $simpleSearchForm = SiteHelpers::configureSimpleSearchForm($tableForm); ?>
            @if(!empty($simpleSearchForm))
                <div class="simpleSearchContainer clearfix">
                    @foreach ($simpleSearchForm as $t)
                        <div class="sscol {{ $t['widthClass'] }}" style="{{ $t['widthStyle'] }}">
                            {!! SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) !!}
                            {!! SiteHelpers::transForm($t['field'] , $simpleSearchForm) !!}
                        </div>
                    @endforeach
                    {!! SiteHelpers::generateSimpleSearchButton($setting) !!}
                </div>
            @endif
        @endif
        
        @include( 'core.users.toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

	 <?php echo Form::open(array('url'=>'core/users/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive">
    @if(!empty($topMessage))
    <h5 class="topMessage">{{ $topMessage }}</h5>
    @endif
	@if(count($rowData)>=1)
    <table class="table table-striped datagrid " id="{{ $pageModule }}Table" data-module="{{ $pageModule }}" data-url="{{ $pageUrl }}">
        <thead>
        <tr>
            @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                <th width="35"> No </th>
            @endif
            @if($setting['disableactioncheckbox']=='false')
                <th width="30"> <input type="checkbox" class="checkall" /></th>
            @endif
            @if($setting['view-method']=='expand') <th>  </th> @endif
            <?php foreach ($tableGrid as $t) :
                if($t['view'] =='1'):
                    $limited = isset($t['limited']) ? $t['limited'] :'';
                    if(SiteHelpers::filterColumn($limited ))
                    {
                        $sortBy = $param['sort'];
                        $orderBy = strtolower($param['order']);
                        $colField = $t['field'];
                        $colIsSortable = $t['sortable'] == '1';
                        $colIsSorted = $colIsSortable && $colField == $sortBy;
                        $colClass = $colIsSortable ? ' dgcsortable' : '';
                        $colClass .= $colIsSorted ? " dgcsorted dgcorder$orderBy" : '';
                        $th = '<th'.
                                ' class="'.$colClass.'"'.
                                ' data-field="'.$colField.'"'.
                                ' data-sortable="'.$colIsSortable.'"'.
                                ' data-sorted="'.($colIsSorted?1:0).'"'.
                                ' data-sortedOrder="'.($colIsSorted?$orderBy:'').'"'.
                                ' style=text-align:'.$t['align'].
                                ' width="'.$t['width'].'"';
                        $th .= '>';
                        $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                        $th .= '</th>';
                        echo $th;
                    }
                endif;
            endforeach; ?>
            @if($setting['disablerowactions']=='false')
                <th width="200"><?php echo Lang::get('core.btn_action') ;?></th>
            @endif
        </tr>
        </thead>

        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0" >
				<td> # </td>
				@if($setting['disableactioncheckbox']=='false')
					<td> </td>
				@endif
				@if($setting['view-method']=='expand') <td> </td> @endif
				@foreach ($tableGrid as $t)
					@if(isset($t['inline']) && $t['inline'] =='1')
					<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
						<td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
							{!! SiteHelpers::transInlineForm($t['field'] , $tableForm) !!}
						</td>
						@endif
					@endif
				@endforeach
				<td >
					<button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
				</td>
			  </tr>
			  @endif

              @foreach ($rowData as $row)
           		<?php $id = $row->id; ?>
                <tr class="editable" id="form-{{ $row->id }}" @if($setting['inline']!='false' && $setting['disablerowactions']=='false') data-id="{{ $row->id }}" ondblclick="showFloatingCancelSave(this)" @endif>
					@if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
						<td class="number"> <?php echo ++$i;?>  </td>
					@endif
					@if($setting['disableactioncheckbox']=='false')
						<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
					@endif
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url('core/users/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
					@endif
					 <?php foreach ($tableGrid as $field) : ?>
                        @if($field['view'] =='1')
                            {{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array()) /*--}}
                            {{--*/ $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn) /*--}}
                        <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}"
                                data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                           @if($field['field'] == 'avatar')
                               <?php if( file_exists( './uploads/users/'.$row->avatar) && $row->avatar !='') { ?>
                               <img src="{{ URL::to('uploads/users').'/'.$row->avatar }} " border="0" width="30" height="30" class="img-circle test" />
                               <?php  } else { ?>
                               <img alt="" src="{{url()}}/silouette.png" width="30" height="30" class="img-circle test" border="0"/>
                               <?php } ?>
                           @elseif($field['field'] =='active')
                                <input type='checkbox' name="mycheckbox" {{($value == "Yes") ?  'checked':'' }}	data-size="mini" data-animate="true"
                                       data-on-text="Active" data-off-text="Inactive" data-handle-width="50px" class="toggle" data-id="{{$row->id}}"
                                       id="toggle_trigger_{{$row->id}}" onSwitchChange="trigger()" />
                            @elseif($field['field'] =='date')
                                {{  DateHelpers::formatDate($value) }}
                            @elseif($field['field'] =='last_login')
                                {{  DateHelpers::formatDateTime($value) }}
                            @elseif($field['field'] =='last_activity')
                                {{  DateHelpers::formatDateTime($value) }}
                            @elseif($field['field'] =='updated_at')
                                {{  DateHelpers::formatDate($value) }}
                            @elseif($field['field'] =='created_at')
                                {{  DateHelpers::formatDate($value) }}
                           @else
                                {{ $value }}                               
                           @endif
                        </td>
                        @endif
                    <?php
						endforeach;
					  ?>
                    @if($setting['disablerowactions']=='false')     
                    <td id="s_icons" data-values="action" data-key="{{ $row->id }}">
                        <div class="action">
					 	@if($access['is_detail'] ==1)
                            <a href="{{ URL::to('core/users/show/'.$row->id.'?return='.$return)}}" class="tips btn btn-xs btn-white" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
                            <a  href="{{ URL::to('core/users/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-white" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
						@endif

                        <a  href="{{ URL::to('core/users/play/'.$row->id)}}" class="tips btn btn-xs btn-white" title="Impersonate"><i class="fa fa-user"  aria-hidden="true"></i></a>

                        @if($row->banned=='1')
                            <a  href="{{ URL::to('core/users/unblock/'.$row->id)}}" class="tips btn btn-xs btn-white" title="Unblock User" ><i class="fa fa-unlock" aria-hidden="true"></i></a>
                        @else
                            <a  href="{{ URL::to('core/users/block/'.$row->id)}}" class="tips btn btn-xs btn-white"  title="Block User"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        @endif
                        <a href="{{ URL::to('core/users/upload/'.$row->id)}}" class="tips btn btn-xs btn-white"  title="Upload Image"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
                        </div>

                    </td>
                    @endif
                </tr>
                @if($setting['view-method']=='expand')
                <tr style="display:none" class="expanded" id="row-{{ $row->id }}">
                	<td class="number"></td>
                	<td></td>
                	<td></td>
                	<td colspan="{{ $colspan}}" class="data"></td>
                	<td></td>
                </tr>
                @endif
            @endforeach

        </tbody>

    </table>
            @if($setting['inline']!='false' && $setting['disablerowactions']=='false')
                @foreach ($rowData as $row)
                    {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                @endforeach
            @endif
	@else

	<div style="margin:100px 0; text-align:center;">
        @if(!empty($message))
            <p class='centralMessage'>{{ $message }}</p>
        @else
            <p class='centralMessage'> No Record Found </p>
        @endif
	</div>

	@endif
    @if(!empty($bottomMessage))
    <h5 class="bottomMessage">{{ $bottomMessage }}</h5>
    @endif

	</div>
	<?php echo Form::close() ;?>
	@include('footer')

	</div>
</div>
<script>
    $(document).ready(function(){
        $("[id^='toggle_trigger_']").on('switchChange.bootstrapSwitch', function(event, state) {


            var userId=$(this).data('id');
            $.ajax(
                    {
                        type:'POST',
                        url:'users/trigger',
                        data:{isActive:state,userId:userId},
                        success:function(data){
                          if(data.status == "error"){
                            //  notyMessageError(data.message);
                          }
                        }
                    }
            );
        });

        $("[id^='toggle_trigger']").bootstrapSwitch();
        $('input[type="checkbox"],input[type="radio"]').not('.toggle').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    });
</script>
<style>
    .table th.right {
        text-align: right !important;
    }

    .table th.center {
        text-align: center !important;
    }

</style>
