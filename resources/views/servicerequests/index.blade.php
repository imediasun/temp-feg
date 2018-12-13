@extends('layouts.app')

@section('content')
<div class="page-content row">
  <!-- Begin Header & Breadcrumb -->
    <div class="page-header">
      <div class="page-title">
        <h3> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  	  
    </div>
	<!-- End Header & Breadcrumb -->

	<!-- Begin Content -->
	<div class="page-content-wrapper m-t">
		<div class="resultData"></div>
		<div id="{{ $pageModule }}View"></div>			
		<div id="{{ $pageModule }}Grid"></div>
	</div>	
	<!-- End Content -->  
</div>	
@endsection
@section ('beforeheadend')

<link href="{{ asset('sximo/css/tickets.css') }}" rel="stylesheet" type="text/css"/>

@endsection
@section ('beforebodyend')

    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}',
            viewTicketId = @if(empty(@$_GET['view'])) "" @else "{{ \SiteHelpers::encryptID($_GET['view'], true) }}" @endif,
                ticketType = @if(empty(@$_GET['ticket_type'])) "" @else "{{ $_GET['ticket_type'] }}" @endif,
            hasViewTicket = viewTicketId && viewTicketId != 0;
        
        $(document).ready(function(){

            if(hasViewTicket){
                ajaxViewDetail('#'+pageModule, pageUrl + "/show/"+viewTicketId+"?ticket_type="+ticketType);
            }
            reloadData('#{{ $pageModule }}','{{ $pageModule }}/data?ticket_type=debit-card-related', UNFN, { isBackground: hasViewTicket});
        });
    </script>
    <script type="text/javascript" src="{{ asset('sximo/js/modules/tickets/grid.js') }}"></script>          
    <script type="text/javascript" src="{{ asset('sximo/js/modules/tickets/view.js') }}"></script>
    <!--<script type="text/javascript" src="{{ asset('sximo/js/modules/tickets/form.js') }}"></script>-->          
    
@endsection