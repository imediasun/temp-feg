<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5> <i class="fa fa-table"></i>  {{ $pageTitle }}</h5>
		<div class="sbox-tools" >
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Clear Search" onclick="reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?search=')"><i class="fa fa-trash-o"></i> Clear Search </a>
			<a href="javascript:void(0)" class="btn btn-xs btn-white tips" title="Reload Data" onclick="reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?return={{ $return }}')"><i class="fa fa-refresh"></i></a>
		</div>        
    </div>
    <div class="sbox-content">
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
            <div class="sscol-submit"><br/>
                <button type="button" name="search" class="doSimpleSearch btn btn-sm btn-primary"> Search </button>		
            </div>
        </div>
        @endif
        @endif        
        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

        <div>
            <p>
                @if(isset($rowData))
                    @foreach($rowData as $row)
                        <a title="{{ $row->Location }}" class="previewImage fancybox" rel='gallery1' data-id="{{ $row->id }}" href="{{ url() }}/uploads/gallary/{{ $row->id }}.jpg">
                            <img src="{{ url() }}/uploads/gallary/{{ $row->id }}_thumb.jpg" alt="{{ $row->theme_name }}" class="merch-gallery"/>
                        </a>
                    @endforeach
                @endif
            </p>
        </div>

    </div>
</div>
@if($setting['inline'] =='true') @include('sximo.module.utility.inlinegrid') @endif
<script>
    $(document).ready(function() {
        $('.tips').tooltip();
        <?php if($setting['view-method'] =='expand') :
                echo AjaxHelpers::htmlExpandGrid();
            endif;
         ?>
        var simpleSearch = $('.simpleSearchContainer');
        if (simpleSearch.length) {
            initiateSearchFormFields(simpleSearch);
            simpleSearch.find('.doSimpleSearch').click(function(event){
                performSimpleSearch.call($(this), {
                    moduleID: '#{{ $pageModule }}', 
                    url: "{{ $pageUrl }}", 
                    event: event,
                    container: simpleSearch
                });
            });        
        }                 
    
        $(".fancybox").each(function() {
            var elm = this,
                $this = $(elm),
                id=$this.data('id'),
                href=$this.attr('href'),
                title=$this.attr('title'),
                deleteLink = "<a href='javascript:void(0);' onclick='confirmDelete("+ id +")' >Delete</a>",
                fancyTitle =  '<div>' + title + '<br />' + deleteLink + '</div>';
                
            $this.data('fancybox-title', fancyTitle);
           
        });
    });
    function confirmDelete(id)
    {
        if(confirm('Are you sure you want to delete this image from gallery?'))
       {
         location.href="{{ url() }}/redemptioncountergallary/delete/"+id;
       }
    }

</script>
<style>
    .table th.right { text-align:right !important;}
    .table th.center { text-align:center !important;}
    .merch-gallery
    {
        height: 155px;
        width: 155px;
        border: 5px silver solid;
        border-radius: 3px;
        padding: 0;
        margin: 3px;
    }
</style>
