<!--Start:: All Theme Styles -->
<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/style.bundle.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<!--End:: All Theme Styles -->
<!--Start:: UI Format -->
<link href="{{asset('assets/css/themes/layout/header/base/light.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/themes/layout/header/menu/light.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/themes/layout/brand/light.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/themes/layout/aside/light.css')}}?v=7.0.5" rel="stylesheet" type="text/css" />
<!--End:: UI Format -->
<link href="{{asset('css/additional-vendors.css')}}" rel="stylesheet" type="text/css" />
@if(App::environment('local'))
<link href="{{asset('css/app.css')}}?v=1.2.1" rel="stylesheet" type="text/css" />
@else
<link href="{{asset('css/app.min.css')}}?v=1.2.1" rel="stylesheet" type="text/css" />
@endif
@yield('page_specific_css')