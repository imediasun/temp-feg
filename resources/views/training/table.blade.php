<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="sbox">
	<div class="sbox-title">
		<h5> <i class="fa fa-table"></i> </h5>
		<div class="sbox-tools" >
			@if(Session::get('gid') ==10)
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
				<div class="col-md-8 col-md-offset-2" style="padding-top: 20px; padding-right: 20px; padding-bottom: 50px; background-color: #ffffff;text-align: justify">
					<h2 class="text-center">Training Videos</h2>
					<hr/>
                    <ul class="videoContainer clearfix">
					@foreach ($rowData as $row)
                        <li class="videoItem clearfix">
                            <h3 class="video-meta">
                                <span class="video-title">{{ $row->video_title }}</span>
                                <span class="video-by">by {{ $row->users }}</span>
                            </h3>
                            <iframe src="https://youtube.com/embed/{{$row->video_path}}" 
                                    width="100%" height="380" 
                                    allowfullscreen="allowfullscreen"
                                    frameborder="0"
                                    class="video-frame"
                                    >                                        
                            </iframe>
                            @if($access['is_remove'] ==1)
                                {!! Form::open(array('url' => 'training/delete/', 'class' => 'video-action-delete', 'method'=> 'POST')) !!}
                                <input type="hidden" name="ids" value="{{ $row->id }}" />
                                <button class="video-action-delete-button"
                                        type="submit"
                                        data-id="{{$row->id}}"
                                        >Delete video</button>
                                </form>
                                {!! Form::close() !!}
                            @endif
                        </li>
					@endforeach
                    </ul>
                    <hr/>
                    <div class="otherTranings">
                    </div>
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
<script type="text/javascript">
    
    var mainUrl = '{{ $pageUrl }}',
        mainModule = '{{ $pageModule }}';
    
    $(document).ready(function() {
        App.modules.training.videos.init({
                'container': $('#'+pageModule+'Grid'),
                'moduleName': pageModule,
                'mainModule': mainModule,
                'url': pageUrl,
                'mainUrl': mainUrl,
            },
            {!! json_encode($rowData) !!}
        );
        
    });
</script>
