<?php $sidebar = SiteHelpers::menus('sidebar');
$selected_loc=\Session::get('selected_location');?>
@if(isset($selected_loc))
<?php $orderData = SiteHelpers::getOrderHistory();
?>

@endif
<nav role="navigation" class="  sideMenuNav navbar-default navbar-static-side">
    <div class="sidebar-collapse sideMenuContainer">
        <ul id="sidemenu" class="nav expanded-menu">
            <li class="logo-header">
                <a id="logo" class="navbar-brand" href="{{ URL::to('dashboard')}}">
                    @if(file_exists(public_path().'/sximo/images/'.CNF_LOGO) && CNF_LOGO !='')
                        <img id="logo-img" src="{{ asset('sximo/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}"/>
                    @else
                        <img src="{{ asset('sximo/images/logo.png')}}" alt="{{ CNF_APPNAME }}"/>
                    @endif
                </a>
                <img id="logo-bar" src="{{ asset('sximo/images/logo_bar.png') }}" />
            </li>


            <div>
                <?php $user_locations=\Session::get('user_locations'); ?>
                @if(isset($user_locations))
                        <li style=" padding: 5px; margin-bottom: 8px;">
                        <?php $uloc = [];  ?>
                        <select id="user_locations"  class="form-control sidebar_loc_dropdown">
                            <?php $userLocations = \Session::get('user_locations') ?>
                            <option disabled selected>Select Your Location</option>
                            @foreach($userLocations as $location) 
							@if (!isset($uloc[$location->id])) 
								<?php $uloc[$location->id] = $location->id;  ?>
                                <option @if($location->id==\Session::get('selected_location')) selected
                                        @endif value="{{ $location->id }}"
                                        data-locationname="{{ $location->location_name }}"> {{ $location->id }} {{ '||' }} {{ $location->location_name }}</option>
                            @endif
							@endforeach
                        </select>
                    </li>
                @endif

            </div>

            @if(isset($selected_loc))
            <li>
                <div class="profile-element" >
                    <h5 id="profile-element-heading">@if($orderData['user_group'] == 'regusers')
                            Location @if($orderData['selected_location'] != 0) {{ $orderData['selected_location'] }} @else {{ 'not set' }}@endif  - Expense Summary
                        @elseif ($orderData['user_group'] == 'distmgr')
                            All {{ SiteHelpers::getRegionName($orderData['reg_id']) }} Locations - Expense Summary
                        @else
                           Location <span class="sub-heading"> {{ SiteHelpers::getLocationName($orderData['selected_location']) }} - Expense Summary  </span>)
                        @endif
                        <span class="sub-heading">Month : {{ $orderData['curMonthFull'] }}</span>
                    </h5>

                    <table class="budget-summery">
                        <tr>
                            <td>Merchandise</td>
                            <td>${{ number_format($orderData['monthly_merch_order_total'], 2, '.', ',') }}</td>
                        </tr>
                        <tr class="border-bottom">

                            <td>Parts & other </td>
                            <td>${{ number_format($orderData['monthly_else_order_total'], 2, '.', ',') }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td>{{ $orderData['curMonthFull'] }} Remaining Merch Budget:</td>
                            <td>
                            @if($orderData['monthly_merch_remaining'] < 0)
                                ${{ number_format($orderData['monthly_merch_remaining'], 2, '.', ',') }}
                            @else
                                ${{ number_format($orderData['monthly_merch_remaining'], 2, '.', ',') }}
                            @endif
                        </tr>
                        <tr>
                            <td> {{$orderData['prevMonthFull'] }} Over/Under Merch Budget:</td>
                            <td>

                                @if($orderData['last_month_merch_remaining'] < 0)
                                ${{ number_format($orderData['last_month_merch_remaining'], 2, '.', ',')}}
                                @else
                                 ${{ number_format($orderData['last_month_merch_remaining'], 2, '.', ',')}}
                                    @endif
                            </td>
                        </tr>

                    </table>

                </div>
            </li>
            @endif
            {{--<li>
                <a href="{{url('throwreport')}}">
                    <i class=""></i> <span class="nav-label">

					                                Throw Report

					</span><span class="fa arrow"></span>
                </a>
            </li>--}}
            @foreach ($sidebar as $menu)
                <?php
                $mName = $menu['menu_name'];
                $mType = $menu['menu_type'];
                $mIsDivider = $mType == 'divider';
                $mUrl = trim($menu['url'] .'');
                if (strpos($mUrl, '/') === 0) {
                    $mUrl = url().$mUrl;
                }
                if ($mType == 'internal') {
                    $module = $menu['module'];
                    $mUrl = URL::to($menu['module']);
                }
                $iconClass = $menu['menu_icons'];
                //$class = Request::segment(1) == $menu['module'] || Request::url() == $mUrl ? "active" : "";
                $class = Request::url() == $mUrl ? "active" : "";
                ?>
                <li class="{{$class}}" >
                    <a  href="{{ $mUrl }}"
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
                                <?php
                                $mName2 = $menu2['menu_name'];
                                $mType2 = $menu2['menu_type'];
                                $mIsDivider2 = $mType2 == 'divider';
                                $mUrl2 = trim($menu2['url'] .'');
                                if (strpos($mUrl2, '/') === 0) {
                                    $mUrl2 = url().$mUrl2;
                                }
                                if ($mType2 == 'internal') {
                                    $module2 = $menu2['module'];
                                    $mUrl2 = URL::to($menu2['module']);
                                }
                                $iconClass2 = $menu['menu_icons'];
                                //$class2 = (Request::segment(1) == $menu2['module'] ||  Request::url() == $mUrl2) && Request::segment(2)!="setting" ? "active" : "";
                                $class2 = Request::url() == $mUrl2 ? "active" : "";
                                // temporary fix for order form opened from Manage FEG Store Requests
                                if (Request::segment(1)==$menu2['module'] && $menu2['module']=="order") {
                                    $class2 = "active";
                                }
                                ?>
                                <li  class="{{$class2}}")>
                                    <a href="{{ $mUrl2 }}" >
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
                                                <?php
                                                $mName3 = $menu3['menu_name'];
                                                $mType3 = $menu3['menu_type'];
                                                $mIsDivider3 = $mType3 == 'divider';
                                                $mUrl3 = trim($menu3['url'] .'');
                                                if (strpos($mUrl3, '/') === 0) {
                                                    $mUrl3 = url().$mUrl3;
                                                }
                                                if ($mType3 == 'internal') {
                                                    $module3 = $menu3['module'];
                                                    $mUrl3 = URL::to($menu3['module']);
                                                }
                                                $iconClass3 = $menu3['menu_icons'];
                                                //$class3 = Request::segment(1) == $menu3['module']||  Request::url() == $mUrl3 ? "active" : "";
                                                $class3 = Request::url() == $mUrl3 ? "active" : "";
                                                ?>
                                                <li class="{{$class3}}">
                                                    <a href="{{ $mUrl3 }}">
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

<?php
    $noLocationModules = array(
            'Holidays',
            'Pre-Employment',
            'Current Employees',
            'ADP Total Source',
            'New Hires',
            'Independent Contractors',
            'Termination',
            'Game Titles',
            'Game Maintenance',
            'Training',
            'Shipping Games',
            'Inventory  / Asset Tags',
            'General Tips',
            'Training Materials',
            'Setting',
            'API Order Restrictions'
    );
?>

@if( count(\Session::get('user_locations'))<=0 && !(in_array($pageTitle , $noLocationModules)) )
<script>
    notyMessageError('In order to run module you must be assigned to at least one location. You have not been assigned to any locations. Please contact an administrator if you believe this to be an error.', {"timeOut":"8000"});
</script>
@endif
