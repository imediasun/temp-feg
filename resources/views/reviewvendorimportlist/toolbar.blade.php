<div class="row c-margin" style="background: white;
    padding: 10px 0px;
    margin: 0px 3px 10px 3px; border: 1px solid #ececec;">
    <div class="col-md-8"><label style="display: inline-block;">Please select a list to Review:</label>
        <select class="select3 selected_vendor" id="selected_vendor" style="width:60%;">
            <option value="0">--Select--</option>
            @foreach($vendors_list as $vendor)
                <option vendor-id="{{ $vendor->vendor_id }}" @if(($importVendorListId == $vendor->id) && $clearSearch == 0) @if(empty($resetOmit)) selected @endif  @endif value="{{ $vendor->id }}">{{ $vendor->vendor_name.'  '.date('m/d/Y h:i a',strtotime($vendor->email_recieved_at)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <input type="button" value="Open List" onclick="filterByVendor($('#selected_vendor'));" class="btn btn-primary">
        <input type="button" value="Save List" id="savelist" onclick="saveImportListRecord();" class="btn btn-primary">
        <input type="button" value="Delete List" onclick="deleteImportRecord();"  class="btn btn-danger" style="background-color:#fd4b4b !important; border-color:#fd4b4b !important; ">
    </div>
</div>
<div class="row c-margin">
	<div class="col-md-9">
        <input type="button" value="Exclude Item(s)" onclick="omittItem();"  class="btn btn-primary">

        <input type="button" value="Remove from excluded filter" onclick="unomittItem($('#selected_vendor'));"  class="btn btn-primary">


            @if($hideOmittedItems == 0)
                <input type="button" id="omittedItemsShowHide" value="Hide Excluded Items" data-hide-same-item="1"  onclick="showVendorOmittedItems(this,$('#selected_vendor'))"   class="btn btn-primary">
            @else
                <input type="button" id="omittedItemsShowHide" value="Show Excluded Items" data-hide-same-item="0"  onclick="showVendorOmittedItems(this,$('#selected_vendor'))"   class="btn btn-primary">
            @endif
            @if($hideUnchanged == 0)
                <input type="button" id="unchangedItemsShowHide" value="Hide Unchanged Items" data-hide-same-item="1" onclick="hideUnchanged(this,$('#selected_vendor'));"   class="btn btn-primary">
            @else
                <input type="button" id="unchangedItemsShowHide" value="Show Unchanged Items" data-hide-same-item="0" onclick="hideUnchanged(this,$('#selected_vendor'));"   class="btn btn-primary">
            @endif





    </div>
	<div class="col-md-3 ">
        <?php
            $isExcel = isset($access['is_excel']) && $access['is_excel'] == 1;
            $isCSV = isset($access['is_csv'])  ? ($access['is_csv'] == 1) : $isExcel;
            $isPDF = isset($access['is_pdf'])  && $access['is_pdf'] == 1;
            $isWord = isset($access['is_word'])  && $access['is_word'] == 1;
            $isPrint = isset($access['is_print'])  ? ($access['is_print'] == 1) : $isExcel;
            $isExport = $isExcel || $isCSV || $isPDF || $isWord || $isPrint;
        ?>
		@if($isExport)
		{{--<div class="pull-right">--}}
            {{--@if($isExcel)--}}
			{{--<a href="{{ URL::to( $pageModule .'/export/excel?exportID='.uniqid('excel', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>--}}
			{{--@endif--}}
            {{--@if($isCSV)--}}
			{{--<a href="{{ URL::to( $pageModule .'/export/csv?exportID='.uniqid('csv', true).'&return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>--}}
			{{--@endif--}}
            {{--@if($isPDF)--}}
			{{--<a href="{{ URL::to( $pageModule .'/export/pdf?exportID='.uniqid('pdf', true).'&return='.$return) }}" class="btn btn-sm btn-white"> PDF</a>--}}
			{{--@endif--}}
            {{--@if($isWord)--}}
			{{--<a href="{{ URL::to( $pageModule .'/export/word?exportID='.uniqid('word', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Word</a>--}}
			{{--@endif--}}
            {{--@if($isPrint)--}}
			{{--<a href="{{ URL::to( $pageModule .'/export/print?exportID='.uniqid('print', true).'&return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>--}}
			{{--@endif--}}
		{{--</div>--}}
		@endif
	</div>
</div>
<script>
    $(document).ready(function(){
        $(".select3").select2();

        var config_id=$("#col-config").val();
        if(config_id ==0 )
        {
            $('#edit-cols,#delete-cols').hide();
        }
        else
        {
            $('#edit-cols,#delete-cols').show();
        }
    });
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val() + getFooterFilters());
    });
    $(function () {
        @if($setRecent == 1)
        filterByVendor($('#selected_vendor'));
        @endif
    })
</script>