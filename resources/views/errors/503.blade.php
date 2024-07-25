<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		@include('layouts._include-meta',['overide_title' => '503 Internal Server Error'])
		@include('layouts._include-css')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Error-->
			<div class="error error-5 d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url('/assets/media/error/bg5.jpg');">
				<!--begin::Content-->
				<div class="container d-flex flex-row-fluid flex-column justify-content-md-center p-12">
					<a href="{{route('home')}}">
						<img src="{{asset('img/error-logo.png')}}" style="padding-left: 110px;" class="max-h-100px" alt="" />
					</a>
					<h1 class="error-title font-weight-boldest mt-10 mt-md-0 mb-12" style="color:#F9AD3B">503</h1>
					<p class="font-weight-boldest display-4">Something went wrong</p>
					<p class="font-size-h3">
						Service Unavailable, Be Right Back.
					</p>
				</div>
				<!--end::Content-->
			</div>
			<!--end::Error-->
		</div>
		<!--end::Main-->
		@include('layouts._include-js')
		<!--end::Page Scripts-->
	</body>
	<!--end::Body-->
</html>

