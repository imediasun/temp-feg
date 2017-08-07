<div class="row c-margin">
    <div class="col-md-8">
        @if($access['is_add'] ==1)
            {!! AjaxHelpers::buttonActionCreate($pageModule,$setting,'Add Video Link22') !!}
        @endif

                <a  href="{{ url('core/pages/update/'.$pageModule.'?return='.$return) }}" class="tips btn btn-xs btn-white editButtonOnGridRow" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>

    </div>

</div>
<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
</script>