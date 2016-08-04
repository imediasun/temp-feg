<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
				@if(Session::get('gid') ==1)
			<a href="{{ url('feg/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
			@endif
		</div>
	</div>
	<div class="sbox-content">
        @include( $pageModule.'/toolbar')
	 <div>
    @if(!empty($topMessage))
    <h5 class="topMessage">{{ $topMessage }}</h5>
    @endif

	@if(count($rowData)>=1)
            <div class="col-md-8 col-md-offset-2" style="padding-top: 20px; padding-right: 50px; padding-bottom: 50px; background-color: #ffffff;text-align: justify">
                <h2 class="text-center">Training Videos</h2>
                <hr/>
               @foreach ($rowData as $row)
                <p><strong>{{ $row->video_title }} </strong><span style="color: #808080;">by {{ $row->users }}</span></p>
                <p><iframe src="https://youtube.com/embed/{{$row->video_path}}" width="675" height="380" allowfullscreen="allowfullscreen"></iframe></p>
                @endforeach
            </div>
            <div class="clearfix">&nbsp;</div>
	@else
	<div style="margin:100px 0; text-align:center;">
        @if(!empty($message))
            <p class='centralMessage'>{{ $message }}</p>
        @else
            <p class='centralMessage'> No Record Found </p>
        @endif
	</div>
	@endif
    @if(!empty($bottomMessage))
    <h5 class="bottomMessage">{{ $bottomMessage }}</h5>
    @endif
	</div>
	</div>
</div>

