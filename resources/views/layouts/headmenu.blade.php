<div class="row  ">

    <nav style="margin-bottom: 0;" role="navigation" class="navbar navbar-static-top nav-inside">
        <div class="navbar-header">
            <a href="javascript:void(0)" class="navbar-minimalize minimalize-btn btn  silver-btn "><i
                        class="fa fa-bars"></i> </a>
            <span class="navbar-minimalize minimalize-btn text-gray page-title">@if(isset($pageTitle)){{ $pageTitle }}@endif</span>
        </div>
        <ul class="nav navbar-top-links navbar-right">
                <li >
                    <?php if(\Session::get('return_id') != ''): $id = \Session::get('return_id'); ?>
                    <a class="exit-admin" style="color: #428bca;" href="{{ URL::to('core/users/play/'.$id)}}">Exit to Admin</a>
                    <?php endif; ?>
                </li>
            <li>
                <a href="addtocart"  class="dropdown-toggle count-info">
                    <?php
                        $cart_value=\Session::get('total_cart');
                    $cart_value=isset($cart_value)?$cart_value:0;

                    ?>
                    <i class="fa fa-shopping-cart"></i> <span class="notif-alert label label-danger" id="update_text_to_add_cart"></span>

                </a>
            </li>



            @if(CNF_MULTILANG ==1)
                <li class="user dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i
                                class="icon-flag"></i><i class="caret"></i></a>
                    <ul class="dropdown-menu dropdown-menu-right icons-right">
                        @foreach(SiteHelpers::langOption() as $lang)
                            <li><a href="{{ URL::to('home/lang/'.$lang['folder'])}}"><i
                                            class="icon-flag"></i> {{  $lang['name'] }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(Auth::user()->group_id == 10)
                <li class="user dropdown"><a class="dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"><i
                                class="fa fa-desktop"></i> <span>{{ Lang::get('core.m_controlpanel') }}</span><i
                                class="caret"></i></a>
                    <ul class="dropdown-menu dropdown-menu-right icons-right">

                        <li><a href="{{ URL::to('feg/config')}}"><i
                                        class="fa  fa-wrench"></i> {{ Lang::get('core.m_setting') }}</a></li>
                        <li><a href="{{ URL::to('core/users')}}"><i
                                        class="fa fa-user"></i> {{ Lang::get('core.m_users') }}
                                &  {{ Lang::get('core.m_groups') }} </a></li>
                       
                        <li><a href="{{ URL::to('core/logs')}}"><i
                                        class="fa fa-clock-o"></i> {{ Lang::get('core.m_logs') }}</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('core/pages')}}"><i
                                        class="fa fa-copy"></i> {{ Lang::get('core.m_pagecms')}}</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('feg/system/tasks')}}"><i
                                        class="fa fa-tasks"></i> {{ Lang::get('core.m_taskspage')}}</a></li>
                        <li><a href="{{ URL::to('feg/system/systememailreportmanager')}}">
                                <i class="fa fa-envelope-o"></i>
                                {{ Lang::get('core.m_systememailreportmanager')}}
                        </a></li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('feg/module')}}"><i
                                        class="fa fa-cogs"></i> {{ Lang::get('core.m_codebuilder') }}</a></li>
                        <li><a href="{{ URL::to('feg/tables')}}"><i class="icon-database"></i> Database Tables </a>
                        </li>
                        <li><a href="{{ URL::to('feg/menu')}}"><i
                                        class="fa fa-sitemap"></i> {{ Lang::get('core.m_menu') }}</a></li>



                    </ul>
                </li>
            @endif
            <li class="user dropdown"><a class="dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"><i
                            class="fa fa-user"></i> <span>{{ Lang::get('core.m_myaccount') }}</span><i
                            class="caret"></i></a>
                <ul class="dropdown-menu dropdown-menu-right icons-right">
                    <li><a href="{{ URL::to('dashboard')}}"><i
                                    class="fa  fa-laptop"></i> {{ Lang::get('core.m_dashboard') }}</a></li>
                    <li><a href="{{ URL::to('')}}" target="_blank"><i class="fa fa-desktop"></i> Main Site </a></li>
                    <li><a href="{{ URL::to('user/profile')}}"><i
                                    class="fa fa-user"></i> {{ Lang::get('core.m_profile') }}</a></li>

                    <li><a href="{{ URL::to('user/logout')}}"><i
                                    class="fa fa-sign-out"></i> {{ Lang::get('core.m_logout') }}</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>

 <?php $pageModule=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$pageModule;
 if($pageModule == url('').'/' )
     {
          $pageModule='dashboard';
     }
else
    {
    $pageModule=\Route::getFacadeRoot()->current()->uri();
    $pageModule=explode('/',$pageModule);
    $pageModule=$pageModule[0];
}
 ?>

<script>
    $(document).ready(function () {

    });
    $("#user_locations").on('change', function () {
        var location_id = $(this).val();
        var pageModule ="{{ $pageModule }}";
        url=pageModule+"/changelocation/" + location_id;
        window.location.href ="{{ url() }}/"+pageModule+"/changelocation/" + location_id;
    });

</script>