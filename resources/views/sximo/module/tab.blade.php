<ul class="nav nav-tabs" style="margin-bottom:10px;">
  <li @if($active == 'config') class="active" @endif ><a href="{{ URL::to('feg/module/config/'.$module_name)}}">Info</a></li>
  <li @if($active == 'sql') class="active" @endif >
  @if(isset($type) && $type =='report')
  <li @if($active == 'table') class="active" @endif >
  <a href="{{ URL::to('feg/module/table/'.$module_name)}}">Table</a></li>
  @elseif(isset($type) && $type =='generic')


  @else
  <a href="{{ URL::to('feg/module/sql/'.$module_name)}}">SQL</a></li>
  <li @if($active == 'table') class="active" @endif >
  <a href="{{ URL::to('feg/module/table/'.$module_name)}}">Table</a></li>
  <li @if($active == 'form') class="active" @endif >
  <a href="{{ URL::to('feg/module/form/'.$module_name)}}">Form</a></li>
  <li @if($active == 'sub') class="active" @endif >
  <a href="{{ URL::to('feg/module/sub/'.$module_name)}}">Master Detail</a></li>
  @endif
  <li @if($active == 'permission') class="active" @endif >
  <a href="{{ URL::to('feg/module/permission/'.$module_name)}}">Permission</a></li>
  <li @if($active == 'special-permissions') class="active" @endif >
    <a href="{{ URL::to('feg/module/special-permissions/'.$module_name)}}">Special Permissions</a>
  </li>
   <li @if($active == 'rebuild') class="active" @endif >
    @if(isset($type) && ( $type =='report' or $type =='generic'))
    @else
    <a href="javascript://ajax" onclick="SximoModal('{{ URL::to('feg/module/build/'.$module_name)}}','Rebuild Module ')">Rebuild</a>
   </li>
   @endif
</ul>