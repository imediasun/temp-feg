@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<div class="page-content row">
  <!-- Begin Header & Breadcrumb -->
    <div class="page-header">
      <div class="page-title">
        <h3> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>
    </div>
	<!-- End Header & Breadcrumb -->

	<!-- Begin Content -->
	<div class="page-content-wrapper m-t">
		<div class="resultData"></div>
		<div id="{{ $pageModule }}View"></div>			
		<div id="{{ $pageModule }}Grid"></div>
	</div>	
	<!-- End Content -->  
</div>	
<script>
$(document).ready(function(){
	reloadData('#{{ $pageModule }}','{{ $pageModule }}/data');
    $(document).on('dblclick', '.editable', onInlineEditingSelectProperProductTypeAndSubType);
});

function onInlineEditingSelectProperProductTypeAndSubType(){
    var productTypeId = $(this).children('td').children('select[name="prod_type_id"]').val();
    var productSubType = $(this).children('td').children('select[name="prod_sub_type_id"]');
    var productSubTypeId = productSubType.val();
    var productTypeSelectField      =   $(this).children('td').children('select[name="prod_type_id"]');
    getProductSubTypes(productTypeId, ['prod_sub_type_id'], productTypeSelectField)

    setTimeout(function () {
        productSubType.val(productSubTypeId).trigger('change');
    }, 1000)
}
    
function cancelAction() {
    $('#{{$pageModule}}View').hide();
    $('#{{$pageModule}}Grid').show();
    $('#{{$pageModule}}View').html('');
    $("[id^='toggle_trigger_']").iCheck('destroy');
    $("[id^='toggle_trigger_']").bootstrapSwitch( {onColor: 'default', offColor:'primary'});
}

function showAction() {
    $('#{{$pageModule}}View').show();
    $('#{{$pageModule}}Grid').hide();
}

/**
 * this function calls when all simple search operation has been completed.
 * override this function in products module so it populate correct product subtype after simple search has been performed
 */
App.simpleSearch.populateFields = function()  {
    var container = $('.simpleSearchContainer');
    if (container.length) {
        App.populateFieldsFromCache(container, App.simpleSearch);
        $('select[name="prod_type_id"]').trigger('change');
    }
};

</script>	
@endsection
