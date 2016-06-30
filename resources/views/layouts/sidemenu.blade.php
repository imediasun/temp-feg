<?php $sidebar = SiteHelpers::menus('sidebar');
$selected_loc=\Session::get('selected_location');?>
@if(isset($selected_loc))
<?php $orderData = SiteHelpers::getOrderHistory();
?>

@endif
<nav role="navigation" class="navbar-default navbar-static-side">
    <div class="sidebar-collapse">
        <ul id="sidemenu" class="nav expanded-menu">
            <li class="logo-header">
                <a class="navbar-brand" href="{{ URL::to('dashboard')}}">
                    @if(file_exists(public_path().'/sximo/images/'.CNF_LOGO) && CNF_LOGO !='')
                        <img src="{{ asset('sximo/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}"/>
                    @else
                        <img src="{{ asset('sximo/images/logo.png')}}" alt="{{ CNF_APPNAME }}"/>
                    @endif
                </a>
            </li>
            <li class="nav-header">
                <div class="dropdown profile-element" style="text-align:center;"> <span>
				{!! SiteHelpers::avatar() !!}
				 </span>
                    <a href="{{ URL::to('user/profile') }}">
				<span class="clear"> <span class="block m-t-xs"> <strong
                                class="font-bold">{{ Session::get('fid') }}</strong>
				 <br/>

				 </span> 
				 </span>
                    </a>
                </div>
                <div class="photo-header "> {!! SiteHelpers::avatar( 40 ) !!}</div>
            </li>
            @if(isset($selected_loc))
            <li>
                <div class="profile-element" style="color:#FFF;padding:6px;border:1px solid #FFF;box-sizing: border-box">
                    <h4>@if($orderData['user_group'] == 'regusers')
                            Location {{ $orderData['selected_location'] }} - Expense Summary
                        @elseif ($orderData['user_group'] == 'distmgr')
                            All {{ SiteHelpers::getRegionName($orderData['reg_id']) }} Locations - Expense Summary
                        @else
                            All Locations - Expense Summary
                        @endif
                    </h4>
                    <table style="font-size: 10px">
                        <tr>
                            <td>Merch Expences For {{ $orderData['curMonthFull'] }}</td>
                            <td>$ {{ number_format($orderData['monthly_merch_order_total'], 2, '.', ',') }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid lightgray">

                            <td>Parts & other Expense for {{ $orderData['curMonthFull'] }}</td>
                            <td>$ {{ number_format($orderData['monthly_else_order_total'], 2, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td> {{$orderData['prevMonthFull'] }} Over/Under Merch Budget:</td>
                            <td>
                                $
                                @if($orderData['last_month_merch_remaining'] < 0)
                                {{ number_format($orderData['last_month_merch_remaining'], 2, '.', ',')}}
                                @else
                                    {{ number_format($orderData['last_month_merch_remaining'], 2, '.', ',')}}
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $orderData['curMonthFull'] }} Remaining Merch Budget:</td>
                            <td>$
                                @if($orderData['monthly_merch_remaining'] < 0){
                                {{ number_format($orderData['monthly_merch_remaining'], 2, '.', ',') }}
                                 @else
                                {{ number_format($orderData['monthly_merch_remaining'], 2, '.', ',') }}
                            @endif
                        </tr>
                    </table>

                </div>
            </li>
            @endif
            @foreach ($sidebar as $menu)
                <li @if(Request::segment(1) == $menu['module']) class="active" @endif>
                    <a
                    @if($menu['menu_type'] =='external')
                        href="{{ $menu['url'] }}"
                        @else
                        href="{{ URL::to($menu['module'])}}"
                            @endif

                    @if(count($menu['childs']) > 0 ) class="expand level-closed" @endif>
                        <i class="{{$menu['menu_icons']}}"></i> <span class="nav-label">
					
					@if(CNF_MULTILANG ==1 && isset($menu['menu_lang']['title'][Session::get('lang')]))
                                {{ $menu['menu_lang']['title'][Session::get('lang')] }}
                            @else
                                {{$menu['menu_name']}}
                            @endif
					
					</span><span class="fa arrow"></span>
                    </a>
                    @if(count($menu['childs']) > 0)
                        <ul class="nav nav-second-level">
                            @foreach ($menu['childs'] as $menu2)
                                <li @if(Request::segment(1) == $menu2['module']) class="active" @endif>
                                    <a
                                    @if($menu2['menu_type'] =='external')
                                        href="{{ $menu2['url']}}"
                                        @else
                                        href="{{ URL::to($menu2['module'])}}"
                                            @endif
                                            >
                                        <i class="{{$menu2['menu_icons']}}"></i>
                                        @if(CNF_MULTILANG ==1 && isset($menu2['menu_lang']['title'][Session::get('lang')]))
                                            {{ $menu2['menu_lang']['title'][Session::get('lang')] }}
                                        @else
                                            {{$menu2['menu_name']}}
                                        @endif
                                    </a>
                                    @if(count($menu2['childs']) > 0)
                                        <ul class="nav nav-third-level">
                                            @foreach($menu2['childs'] as $menu3)
                                                <li @if(Request::segment(1) == $menu3['module']) class="active" @endif>
                                                    <a
                                                    @if($menu['menu_type'] =='external')
                                                        href="{{ $menu3['url'] }}"
                                                        @else
                                                        href="{{ URL::to($menu3['module'])}}"
                                                            @endif

                                                            >
                                                        <i class="{{$menu3['menu_icons']}}"></i>
                                                        @if(CNF_MULTILANG ==1 && isset($menu3['menu_lang']['title'][Session::get('lang')]))
                                                            {{ $menu3['menu_lang']['title'][Session::get('lang')] }}
                                                        @else
                                                            {{$menu3['menu_name']}}
                                                        @endif

                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>	  
	  
