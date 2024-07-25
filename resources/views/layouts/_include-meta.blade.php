<meta charset="utf-8" />
@if(isset($overide_title))
<title> {{$overide_title}} | {{config('app.name')}}</title>
@else
<title> @yield('page_title', __('auth/frontend/heading.login')) | {{config('app.name')}}</title>
<link rel="icon" href="{{asset('img/amounee-logo2.png')}}" type="image/x-icon">
@endif
<meta name="keywords" content="">
<meta name="description" content="">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut" href="{{asset('img/favicon.ico')}}" />
<link rel="icon" type="image/png" href="{{asset('img/favicon.ico')}}" />