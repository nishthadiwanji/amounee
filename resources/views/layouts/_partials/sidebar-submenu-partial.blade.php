<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
    <a href="{{isset($list['url']) ? route($list['url']) : 'javascript:;'}}" class="menu-link menu-toggle">
	    <i class="{{$list['icon']}} menu-icon"></i>
		<span class="menu-text">{{$list_title}}</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <i class="menu-arrow"></i>
        <ul class="menu-subnav">
            @foreach($list['sub_menu'] as $sub_menu_item_name => $sub_menu_item)
                @if(isset($sub_menu_item['sub_menu']))
                    @include('layouts._partials.sidebar-submenu-partial',['list_title' => $sub_menu_item_name, 'list' => $sub_menu_item])
                @else
                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{isset($sub_menu_item['url']) ? route($sub_menu_item['url']) : 'javascript:;'}}" class="menu-link">
							{{-- <i class="menu-bullet menu-bullet-dot">
                                <span></span>
                            </i> --}}
                            <i class="{{$sub_menu_item['icon']}} menu-icon"><span></span></i>
                            <span class="menu-text">{{$sub_menu_item_name}}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</li>