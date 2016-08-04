<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
    <div class="sbox-title">
        <h5> <i class="fa fa-table"></i>  {{ $pageTitle }}</h5>
    </div>
    <div class="sbox-content">
        @include( $pageModule.'/toolbar',['config_id'=>$config_id,'colconfigs' => SiteHelpers::getRequiredConfigs($module_id)])

        <div>
            <p>
                @if(isset($rowData))
                    @foreach($rowData as $row)
                        <a title="{{ $row->Location }}" class="previewImage fancybox" rel='gallery1' data-id="{{ $row->id }}" href="{{ url() }}/uploads/gallary/{{ $row->id }}.jpg">
                            <img src="{{ url() }}/uploads/gallary/{{ $row->id }}_thumb.jpg" alt="{{ $row->theme_name }}" class="merch-gallery"/>
                        </a>
                    @endforeach;
                @endif
            </p>
        </div>

    </div>
</div>
@if($setting['inline'] =='true') @include('feg.module.utility.inlinegrid') @endif
<script>
    $(document).ready(function() {
        $('.tips').tooltip();
        <?php if($setting['view-method'] =='expand') :
                echo AjaxHelpers::htmlExpandGrid();
            endif;
         ?>
    });
    $(".fancybox").each(function() {
        var id=$(this).data('id');
        var href=$(this).attr('href');
        var title=this.title;
        var title ="'"+title+"'";
        var data=[id,title];
                if (this.title) {
                    // New line
                    this.title += '<br />';
                    // Add tweet button
                    this.title += '<a href="#" onclick="confirmDelete('+ id +','+title+')" >Delete</a> ';
                }
    });
    function confirmDelete(id,title)
    {
       if(confirm('Are you sure you want to delete '+title))
       {
         location.href="{{ url() }}/redemptioncountergallary/delete/"+id;
       }
        else
       {

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
