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
                @foreach ($tableGrid as $field) 
                    @if($field['view'] =='1' && isset($row->$field['field'])) 
                        {{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
                        {{--*/ $value = AjaxHelpers::gridFormater($row->$field['field'], $row, $field['attribute'], $conn) /*--}}                            
                        {{--*/ $limited = isset($field['limited']) ? $field['limited'] :''/*--}} 
                        @if(SiteHelpers::filterColumn($limited ))
                        <tr>
                            <td width='30%' class='label-view text-right'>
                                {!! \SiteHelpers::activeLang($field['label'],(isset($field['language'])? $field['language'] : array())); !!}
                            </td>
                             <td >
                                     {!! $value !!}
                             </td>
                        </tr>
                        @endif
                    @endif
                @endforeach
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