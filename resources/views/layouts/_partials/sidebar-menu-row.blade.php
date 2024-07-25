<li class="menu-item" aria-haspopup="true">
    <a href="{{isset($list['url']) ? route($list['url']) : 'javascript:;'}}" class="menu-link">
        <i class="{{$list['icon']}} menu-icon"></i>
        <span class="menu-text">{{$list_title}}</span>
    </a>
</li>