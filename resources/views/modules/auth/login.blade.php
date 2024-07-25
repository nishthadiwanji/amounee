<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<title>Amounee</title>
	<link rel="icon" href="{{asset('img/amounee-logo2.png')}}" type="image/x-icon">
	@include('layouts._include-meta')
	@include('layouts._include-css')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Login-->
		<div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
			<!--begin::Aside-->
			<div class="login-aside order-1 order-lg-2 d-flex flex-row-auto position-relative overflow-hidden">
				<!--begin: Aside Container-->
				<div class="d-flex flex-column-fluid flex-column justify-content-between py-9 px-7 py-lg-13 px-lg-35" style="background-color: #f7f7f7;">
					<!--begin::Logo-->
					<a href="{{route('home')}}" class="text-center pt-10">
						<img src="{{asset('img/logo-amounee.jpg')}}" class="max-h-75px" alt="" />
					</a>
					<!--end::Logo-->
					<!--begin::Aside body-->
					<div class="d-flex flex-column-fluid flex-column flex-center">
						<!--begin::Signin-->
						<div class="login-form login-signin py-11">
							<!--begin::Form-->
							<form class="form" id="kt_login_signin_form" data-action="{{route('auth.attempt-login')}}" data-redirect-url="{{route('redirect')}}">
								<!--begin::Title-->
								<div class="text-center pb-8">
									<h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h2>
									<p class="text-muted font-weight-bold font-size-h4">to your account</p>
								</div>
								<!--end::Title-->
								<!--begin::Form group-->
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">Email</label>
									<input class="form-control h-auto py-7 px-6 rounded-lg" type="text" name="username" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<div class="d-flex justify-content-between mt-n5">
										<label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
										<a tabindex="-1" href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a>
									</div>
									<input class="form-control  h-auto py-7 px-6 rounded-lg" type="password" name="password" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Action-->
								<div class="text-center pt-2">
									<button id="kt_login_signin_submit" type="button" class="btn btn-success font-weight-bolder font-size-h6 px-8 py-4 my-3 pin-submit">Sign In</button>
								</div>
								<!--end::Action-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Signin-->
						<!--begin::Forgot-->
						<div class="login-form login-forgot pt-11">
							<!--begin::Form-->
							<form class="form" id="kt_login_forgot_form" data-action="{{route('password.attempt-forgot')}}">
								<!--begin::Title-->
								<div class="text-center pb-8">
									<h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Forgotten Password ?</h2>
									<p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
								</div>
								<!--end::Title-->
								<!--begin::Form group-->
								<div class="form-group">
									<input class="form-control h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
									<button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Submit</button>
									<button type="button" id="kt_login_forgot_cancel" class="btn btn-danger font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</button>
								</div>
								<!--end::Form group-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Forgot-->
					</div>
					<!--end::Aside body-->
				</div>
				<!--end: Aside Container-->
			</div>
			<div id="carouselExampleSlidesOnly" class="carousel slide content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-x-center" data-ride="carousel">
				<ol class="carousel-indicators">
					<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
					<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				</ol>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="{{asset('assets/media/svg/illustrations/picture.jpeg')}}" alt="" style="max-width:100%;min-width:100%;">
					</div>
					<div class="carousel-item">
						<img src="{{asset('assets/media/svg/illustrations/Loginpic-2.jpg')}}" alt="" style="max-width:100%;min-width:100%;">
					</div>
				</div>
			</div>
		</div>
		<!--end::Login-->
	</div>
	<!--end::Main-->
	@include('layouts._include-js')
	<!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>