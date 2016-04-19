<div class="row m-b">
	<div class="col-md-8">
		<div class="col-md-4">	@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting) !!}
			@endif
            <a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>
        </div>
        <div class="col-md-4">
            <h2>Merchant Budget</h2>
        </div>
        <div class="col-md-4">
            <?php
            $years=array(2012,2013,2014,2015,2016,2017,2018,2019,2020);
            ?>
            <select name="budget_year" id="budget_year" class="form-control">
                <option selected disabled>         ----- Select Year ----- </option>
                @foreach($years as $year)
                    <option @if($year==\Session::get('budget_year')) selected @endif value="{{ $year}}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

    </div>
</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val());
    });
    $("#budget_year").on('change',function(){

        var val=$(this).val();
        if(val) {
            reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?budget_year='+val);
        }
    });
</script>