@extends('layouts.app')

@section('content')

<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Special Permissions Editor</h3>
        </div>
        @if ($view_mode !== 'solo')
        <ul class="breadcrumb">
            <li><a href="{{ URL::to('dashboard') }}"> Code Builder </a></li>
            <li><a href="{{ URL::to('feg/module') }}"> Modules </a></li>
            <li class="active"> {{ $module_title }} </li>
        </ul>	  
        @endif
    </div>
	<div class="page-content-wrapper m-t" id='specialPermissionsGridContainer'> 
        @if ($view_mode !== 'solo')
            @include('sximo.module.tab', ['active'=>'special-permissions','type'=>$type])
        @endif
        @if(Session::has('message'))
            {!! Session::get('message') !!}
        @endif
        <div class="sbox">
            <div class="sbox-title">
                <h5>{!! $module_title !!}
                    {!! !empty($module_desc) ? ' - ' . $module_desc : '' !!}
                </h5></div>
                <div class="sbox-content">
                    <div class="m-t"></div>	
                    <div class="infobox infobox-danger fade in">
                        <button type="button" class="close" data-dismiss="alert"> x </button>
                        <h5>Please Note:</h5>
                        <p><strong>Special Permissions</strong> are unique to each 
                            module. Be careful while you modify the values.</p>
                    </div>
                    <div class="m-t m-b clearfix" >
                        <button type="button" class="btn btn-success addPermission"                                 
                                > Add </button>                        
                        <button type="button" class="btn btn-success deletePermission"                                 
                                > Delete </button> 
                        <div style='display:none' class='newRowTemplateContainer'>
                            <table><tr 
                                    class="newPermission editable"                                     
                                    data-id=""
                                    >
                                    <td class='rowSelector'>
                                        <input type="checkbox" 
                                               class="ids" 
                                               name="ids[]"
                                               parsley-excluded='true'
                                               data-parsley-excluded='true'
                                               value="" 
                                        />
                                    </td>
                                    @foreach($tableGrid as $field => $item)
                                    
                                        {{--*/ $hidden = $item['hidden'] ? 
                                            'style="display: none"': '' /*--}}
                                        {{--*/ $defaultValue = $field == 'is_active' ? 
                                            '1': '' /*--}}
                                        {{--*/
                                            
                                            extract(\FEGHelp::specialPermissionFormatter(
                                                $defaultValue, 
                                                $item, 
                                                [
                                                    'row' => [], 
                                                    'data' => [], 
                                                    'grid' => $tableGrid,                                                     
                                                    'module_id' => $module_id
                                                ])
                                            ) 
                                    
                                        /*--}}
                                                
                                        <td
                                            class="{!! @$fieldClass !!}"
                                            data-values="{!! htmlentities(@$value) !!}" 
                                            data-field="{!! $field !!}" 
                                            data-format="{!! htmlentities(@$formattedValue) !!}"
                                            {!! $hidden !!} 

                                            ><span class="rowDisplayValue" 
                                               {!! $hideText !!}
                                                >{!! @$value !!}
                                            </span><div class="rowEditor"
                                                {!! $hideInput !!}
                                                >{!! @$input !!}</div>
                                        </td>
                                    @endforeach
                                    <td 
                                        class='actionCell'
                                        style="display: none;" 
                                        data-values="action" 
                                        data-key=""
                                    ></td>
                                </tr></table>
                        </div>
                    </div>                     
                    {!! Form::open([
                        'url'=>'feg/module/save-special-permissions/'.$module_name, 
                        'class'=>'form-horizontal gridForm',
                        'parsley-validate'=>'', 
                        'novalidate'=>' ',
                        'id' => 'specialPermissionsGridContainerForm'
                    ]) !!}
                    <div class="table-responsive">

                        @if(!empty($topMessage))
                        <h5 class="topMessage">{!! $topMessage !!}</h5>
                        @endif

                        <table class="table table-striped datagrid ">
                            <thead>
                                <tr>
                                    <th class='checkAllRowsCell'>
                                        <input type="checkbox" 
                                               class="checkAll" 
                                               name='selectAll' 
                                               parsley-excluded='true'
                                               data-parsley-excluded='true'
                                            />
                                    </th>
                                    {{--*/ $columnCount = count($tableGrid) + 2; /*--}}                                    
                                    @foreach($tableGrid as $field => $item)					
                                        {{--*/ $hidden = @$item['hidden'] ? 
                                            'style="display: none"': '' /*--}}
                                        <th 
                                            class="{!! @$item['colClass'] !!}"
                                            data-field="{!! $field !!}"
                                            {!! $hidden !!}    
                                            
                                        >{!! $item['label'] !!}</th>
                                    @endforeach
                                    <th width="70" style="display: none;" >
                                        {!! Lang::get('core.btn_action') !!}
                                    </th>
                                  </tr>
                            </thead>
                            @if(($hasData = count($rowData)> 0))
                            <tbody>
                                @foreach ($rowData as $row)                
                                {{--*/ $rowId = $row->id; /*--}}
                                <tr 
                                    class="editable" 
                                    id="form-{!! $rowId !!}" 
                                    data-id="{!! $rowId !!}"
                                >
                                    <td class='rowSelector'>
                                        <input type="checkbox" 
                                               class="ids" 
                                               name="ids[]"
                                               parsley-excluded='true'
                                               data-parsley-excluded='true'
                                               value="{!! $rowId !!}" 
                                        />
                                    </td>
                                    @foreach($tableGrid as $field => $item)
                                    
                                        {{--*/ $hidden = $item['hidden'] ? 
                                            'style="display: none"': '' /*--}}
                                        {{--*/
                                            
                                            extract(\FEGHelp::specialPermissionFormatter(
                                                $row->$field, 
                                                $item, 
                                                [
                                                    'row' => $row, 
                                                    'data' => $rowData, 
                                                    'grid' => $tableGrid,                                                     
                                                    'module_id' => $module_id
                                                ])
                                            ) 
                                    
                                        /*--}}
                                                
                                        <td
                                            class="{!! @$fieldClass !!}"
                                            data-values="{!! htmlentities(@$value) !!}" 
                                            data-field="{!! $field !!}" 
                                            data-format="{!! htmlentities(@$formattedValue) !!}"
                                            {!! $hidden !!} 

                                            ><span class="rowDisplayValue" 
                                               {!! $hideText !!}
                                                >{!! @$value !!}
                                            </span><div class="rowEditor"
                                                {!! $hideInput !!}
                                                >{!! @$input !!}</div>
                                        </td>

                                    @endforeach

                                    <td 
                                        class='actionCell'
                                        style="display: none;" 
                                        data-values="action" 
                                        data-key="{!! $rowId !!}"
                                    ></td>

                                </tr>
                                <tr 
                                    class="expanded" 
                                    data-id="{!! $rowId !!}"
                                    style="display:none"
                                >
                                    <td colspan='{!! $columnCount !!}'></td>
                                </tr>
                                @endforeach
                            </tbody>
                            @else 
                            <tbody>
                                <tr><td colspan="20">
                                    <div style="margin:100px 0; text-align:center;">
                                        @if(!empty($message))
                                            <p class='centralMessage'>
                                                {!! $message !!}
                                            </p>
                                        @else
                                            <p class='centralMessage'>
                                                No Special Permission found
                                            </p>
                                        @endif
                                    </div>                                      
                                </td></tr>
                            </tbody>
                            @endif
                        </table>                      
                        @if(!empty($bottomMessage))
                        <h5 class="bottomMessage">{!! $bottomMessage!!}</h5>
                        @endif    

                    </div>
                    <div class="m-t m-b clearfix" >
                        <button type="submit" class="btn btn-success" 
                                @if(!$hasData) disabled @endif
                                > Save Changes </button>	
                        <input name="module_id" type="hidden" value="{!! $module_id !!}" />        
                    </div>        
                    {!! Form::close() !!}	
            </div>
        </div>
    </div>
</div>

@endsection
@section ('beforeheadend')

<link href="{{ asset('sximo/js/plugins/datatables/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('sximo/css/special-permissions.css') }}" rel="stylesheet" type="text/css"/>

@endsection
@section ('beforebodyend')

    <script type="text/javascript" src="{{ asset('sximo/js/plugins/datatables/dataTables.bootstrap.js') }}"></script>  
    <script type="text/javascript" src="{{ asset('sximo/js/modules/config/special-permissions.js') }}"></script>  
    <script type="text/javascript" >
    $(document).ready(function() {
        App.modules.specialPermissions.grid.init({
                'container' :  $('#specialPermissionsGridContainer'),                                                    
                'moduleName':  '$module_id',
                'moduleId'  :  '$module_id'
            },
            {                
                'permissions':  {!! json_encode($rowData)   !!}, 
                'grid'       :  {!! json_encode($tableGrid) !!}, 
            }
        );
        
    });
    </script>

@endsection
