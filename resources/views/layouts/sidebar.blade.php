<!--begin::Aside Menu-->
<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
	<div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
		<ul class="menu-nav">
            @foreach(config('sidemenu.'.Sentinel::getUser()->roles->first()->slug) as $list_title => $list)
                @if(isset($list['sub_menu']))
                    @include('layouts._partials.sidebar-submenu-partial',['list_title' => $list_title, 'list' => $list])
                @else
                    @include('layouts._partials.sidebar-menu-row',['list_title' => $list_title, 'list' => $list])
                @endif
            @endforeach
        </ul>
    </div>
</div>