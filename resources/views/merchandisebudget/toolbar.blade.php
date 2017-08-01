<div class="row c-margin">
    <div class="col-md-4">
        @if($access['is_add'] ==1)
        {!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
        @endif
        <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
    </div>
    <div class="col-md-4">
        <?php
            $years=SiteHelpers::getBudgetYears()
        ?>
        <select name="budget_year" id="budget_year" class="selectpicker1 show-menu-arrow" data-header="Select Year" data-style="btn-default">
            @foreach($years as $year)
                <option @if( $year->year == \Session::get('budget_year')) selected
                                                                          @endif value="{{ $year->year }}">{{ $year->year }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <?php
        $isExcel = isset($access['is_excel']) && $access['is_excel'] == 1;
        $isCSV = isset($access['is_csv'])  ? ($access['is_csv'] == 1) : $isExcel;
        $isPDF = isset($access['is_pdf'])  && $access['is_pdf'] == 1;
        $isWord = isset($access['is_word'])  && $access['is_word'] == 1;
        $isPrint = isset($access['is_print'])  ? ($access['is_print'] == 1) : $isExcel;
        $isExport = $isExcel || $isCSV || $isPDF || $isWord || $isPrint;
        ?>
        @if($isExport)
            <div class="pull-right">
                @if($isExcel)
                    <a href="{{ URL::to( $pageModule .'/export/excel?exportID='.uniqid('excel', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
                @endif
                @if($isCSV)
                    <a href="{{ URL::to( $pageModule .'/export/csv?exportID='.uniqid('csv', true).'&return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
                @endif
                @if($isPDF)
                    <a href="{{ URL::to( $pageModule .'/export/pdf?exportID='.uniqid('pdf', true).'&return='.$return) }}" class="btn btn-sm btn-white"> PDF</a>
                @endif
                @if($isWord)
                    <a href="{{ URL::to( $pageModule .'/export/word?exportID='.uniqid('word', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Word</a>
                @endif
                @if($isPrint)
                    <a href="{{ URL::to( $pageModule .'/export/print?exportID='.uniqid('print', true).'&return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
                @endif
            </div>
        @endif
    </div>
</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    $("#budget_year").on('change',function(){

        var val=$(this).val();
        if(val) {
            reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?budget_year='+val+ getFooterFilters());
        }
    });
$(document).ready(
        function(){
            $('.selectpicker1').selectpicker();
        }
);
</script>
