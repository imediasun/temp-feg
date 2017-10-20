<div class="row c-margin">
    <div class="col-md-8">
        <div class="float-margin">
        	@if($access['is_add'] ==1)
			{!! AjaxHelpers::buttonActionCreate($pageModule,$setting,"Get Freight Quote") !!}
			@endif
                @if($setting['disableactioncheckbox']=='false')
                @if($access['is_remove'] == 1)
                    <a id="removeFright" href="javascript://ajax" class="btn btn-sm btn-white" onclick="ajaxRemoveCustom('#{{ $pageModule }}','{{ $pageUrl }}');"><i class="fa fa-trash-o "></i> {{ Lang::get('core.btn_remove') }} </a>
                @endif
            @endif
            </div>
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white float-margin" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
                <select class="form-control float-margin height-set" id="status" style="display:inline;width:auto;position:relative;top:1px;">

                    <option @if($selected_status == 'requested') selected @endif value="requested" selected>Requested Freight Quotes</option>
                    <option @if($selected_status == 'booked') selected @endif value="booked" >Booked Freight Quotes</option>
                    <option @if($selected_status == 'archive') selected @endif value="archive">Freight Order Archive</option>
                </select>
    </div>
</div>

<script>
    $("#col-config").on('change',function(){
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?config_id='+$("#col-config").val()+ getFooterFilters());
    });
    $("#status").on('change',function(){
        var freight_type=$(this).val();
        reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?status='+freight_type+'&config_id='+$("#col-config").val()+ getFooterFilters());

    });
    function ajaxRemoveCustom( id, url )
    {
        var datas = $( id +'Table :input').serialize();
        if($(".ids:checked").length > 0) {
            var freight_type = $("#status").val();
            if(freight_type == 'archive')
            {
                notyMessageError("You cannot delete Archived Freight Orders!");
            }
            else
            {
                if(confirm('Are you sure you want to delete the selected row(s)?')) {

                    $.post( url+'/delete' ,datas,function( data ) {

                        if(data.status =='success')
                        {
                            //console.log("called succes");
                            notyMessage(data.message);
                            ajaxFilter( id ,url+'/data' );
                        } else {
                            //console.log("called error");
                            notyMessageError(data.message);
                        }
                    });

                }
            }
        }
        else
        {
            notyMessageError("Please select one or more rows.");
        }
    }
</script>
