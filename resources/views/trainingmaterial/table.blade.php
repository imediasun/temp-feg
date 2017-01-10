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
				<p>Click on links below to download training materials..</p>
				<ul>
			         <li><a href="{{asset('trainingmaterial/sacoa_redemption_program_manual_195_eng.doc')}}">Sacoa redemption program manual</a></li>
			         <li><a href="{{asset('trainingmaterial/merch_training_binder.doc')}}">Merch Training Binder</a></li>
					 <li><a href="{{asset('trainingmaterial/arcade_decor.pptx')}}">Arcade Decor PowerPoint</a></li>
					 <li><a href="{{asset('trainingmaterial/throw_percentages.xlsm')}}">Throw Percentages with Reset Macro</a></li>
					 <li><a href="{{asset('trainingmaterial/merchandising_standards.doc')}}">merchandising standards</a></li>
					 <li><a href="{{asset('trainingmaterial/eclaw_standards_guide .doc')}}">E-Claw Standards</a></li>
					 <li><a href="{{asset('trainingmaterial/mega_stacker_standards_guide.doc')}}">Mega Stacker Standards</a></li>
			         <li><a href="{{asset('trainingmaterial/high_end_merchandiser_standards.doc')}}">High-End Merchandisers Standards</a></li>
			         <li><a href="{{asset('trainingmaterial/miscellaneous_merchandisers.doc')}}"> Merchandisers Standards</a></li>
			         <li><a href="{{asset('trainingmaterial/redemption_decor_standards.doc')}}">Redemption Decor Standards</a></li>
				     <li><a href="{{asset('trainingmaterial/traditional_crane_standards.doc')}}">Traditional Crane Standards</a></li>
				     <li><a href="{{asset('trainingmaterial/ticket_bow_101.doc')}}">Ticket Bow 101</a></li>
				     <li><a href="{{asset('trainingmaterial/prize_ordering_101.docx')}}">Prize Ordering 101</a></li>
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

