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
            <p><?php $check_mul="";$show=true; ?>
                @if(isset($rowData))
                 @foreach($rowData as $row)
                        <?php
                            if($check_mul == $row->batch)
                                {
                                    $show=false;
                                }
                            else
                                {
                                    $show=true;
                                }
                        $rel="gallery".$row->batch;
                        $check_mul=$row->batch;
                            $originalFile="./uploads/gallary/". $row->id.".jpg";
                            $rotatedFile="./uploads/gallary/". $row->id."_rotated.jpg";
                            $originalThumbFile="./uploads/gallary/". $row->id."_thumb.jpg";
                            $rotatedThumbFile="./uploads/gallary/".$row->id."_thumb_rotated.jpg";
                        ?>
                <a @if(!$show)) style="display:none" @else style="display:inline" @endif  title="{{ $row->theme_name }} by {{$row->users }} at {{ $row->Location }} " class="previewImage fancybox" data-fancybox-group="{{$rel}}"  rel="{{$rel}}" data-id="{{ $row->id }}"
                @if(file_exists($rotatedFile))
                   href="{{ $rotatedFile }}?time={{ time() }}"
                   @else
                   href="{{ $originalFile }}?time={{ time() }}"
                   @endif
                  >
                    <img
                    @if(file_exists($rotatedThumbFile))
                        src="{{ $rotatedThumbFile  }}?time={{ time() }}"
                    @else
                        src="{{  $originalThumbFile }}?time={{ time() }}"
                    @endif
                    alt="{{ $row->theme_name }}" class="merch-gallery"/>
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
            rotatebtns= '<div class="rotate-section"><button onclick="rotateTo(this)" class="btn btn-primary btn-xs" data-id='+id+' data-value= "+90">+90&deg</buton><button onclick="rotateTo(this)" class="btn btn-primary btn-xs" data-id='+id+' data-value="-90">-90&deg</buton><button onclick="rotateTo(this)" class="btn btn-primary btn-xs" data-id='+id+' data-value="+180">+180&deg</buton><button onclick="rotateTo(this)" class="btn btn-primary btn-xs" data-id='+id+' data-value="-180">-180&deg</buton><button id="rotate_save" onclick="saveRotateImg(this)" class="btn btn-info btn-xs"  data-id='+id+'>Save</button></div>',
            deleteLink = '<a href="javascript:void(0);" onclick="confirmDelete('+ id +',\''+title+'\');" >Delete</a>',
            fancyTitle =  '<div>'+rotatebtns + title + '<br>' + deleteLink + '</div>';
            $this.data('fancybox-title', fancyTitle);
    });
});
function confirmDelete(id, title)
{
    if(confirm('Are you sure you want to delete '+title))
    {
        location.href="{{ url() }}/merchindisetheminggallary/delete/"+id;
    }
}
   var angle=0;
   function rotateTo(ele){

       angle += $(ele).data('value');
       $('.fancybox-inner').css({'transform': 'rotate(' + angle + 'deg)'});

   }
    function saveRotateImg(ele)
    {
        $('.ajaxLoading').show();
        var id=$(ele).data('id');
        $.ajax(
                {
                    type:'POST',
                    url:'merchindisetheminggallary/rotate',
                    data:{id:id,angle:angle},
                    success:function(data){
                        if(data.status == 'success')
                        {
                            notyMessage(data.message);
                          window.location.reload(true);
                        } else {
                            notyMessageError(data.message);
                            $('.ajaxLoading').hide();
                            return false;
                        }
                    }
                }
        );
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
.fancybox-skin
{
    -moz-box-shadow: none!important;
    -webkit-box-shadow: none!important;
    box-shadow: none!important;
    -o-box-shadow: none!important;
    background: none!important;
}
    .fancybox-inner
    {
        border:8px solid rgb(255,255,255);
        webkit-box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        -moz-box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }
</style>
