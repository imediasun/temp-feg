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
            <div class="col-md-8 col-md-offset-2" style="padding-top: 20px; padding-right: 50px; padding-bottom: 50px; background-color: #ffffff;text-align: justify">
                <h2 class="text-center">Training Videos</h2>
                <hr/>
               @foreach ($rowData as $row)
                <p><strong>{{ $row->video_title }} </strong><span style="color: #808080;">by {{ $row->users }}</span></p>
                <p><iframe src="https://youtube.com/embed/{{$row->video_path}}" width="600" height="380" allowfullscreen="allowfullscreen"></iframe></p>
                @endforeach
				<p style="color: blue">Click on links below to download training materials..</p>
				<ul style="color: blue">
			         <li><a style="color: blue" href="{{asset('trainingmaterials/sacoa_redemption_program_manual_195_eng.doc')}}">Sacoa redemption program manual</a></li>
			         <li><a style="color: blue" href="{{asset('trainingmaterials/merch_training_binder.doc')}}">Merch Training Binder</a></li>
					 <li><a style="color: blue" href="{{asset('trainingmaterials/arcade_decor.pptx')}}">Arcade Decor PowerPoint</a></li>
					 <li><a style="color: blue" href="{{asset('trainingmaterials/throw_percentages.xlsm')}}">Throw Percentages with Reset Macro</a></li>
					 <li><a style="color: blue" href="{{asset('trainingmaterials/merchandising_standards.doc')}}">merchandising standards</a></li>
					 <li><a style="color: blue" href="{{asset('trainingmaterials/eclaw_standards_guide.doc')}}">E-Claw Standards</a></li>
					 <li><a style="color: blue" href="{{asset('trainingmaterials/mega_stacker_standards_guide.doc')}}">Mega Stacker Standards</a></li>
			         <li><a style="color: blue" href="{{asset('trainingmaterials/high_end_merchandiser_standards.doc')}}">High-End Merchandisers Standards</a></li>
			         <li><a style="color: blue" href="{{asset('trainingmaterials/miscellaneous_merchandisers.doc')}}"> Merchandisers Standards</a></li>
			         <li><a style="color: blue" href="{{asset('trainingmaterials/redemption_decor_standards.doc')}}">Redemption Decor Standards</a></li>
				     <li><a style="color: blue" href="{{asset('trainingmaterials/traditional_crane_standards.doc')}}">Traditional Crane Standards</a></li>
				     <li><a style="color: blue" href="{{asset('trainingmaterials/ticket_bow_101.doc')}}">Ticket Bow 101</a></li>
				     <li><a style="color: blue" href="{{asset('trainingmaterials/prize_ordering_101.docx')}}">Prize Ordering 101</a></li>
				</ul>
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

