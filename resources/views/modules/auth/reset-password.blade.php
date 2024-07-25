<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
			@lang('auth/frontend/heading.reset_password') | {{config('app.name')}}
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('layouts._include-css')
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
            WebFont.load({
                google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
            WebFont.load({
                google: {"families":["Montserrat:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
		</script>
	</head>
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-login m-login--signin  m-login--5" id="m_login" style="background-image: url({{asset('assets/app/media/img/bg/bg-3.jpg')}});">
				<div class="m-login__wrapper-1 m-portlet-full-height" style="vertical-align:middle; padding-left:32%; padding-top:1%">
					<div class="m-login__wrapper-1-1">
						<div class="m-login__contanier">
							<div class="m-login__content">
								<div class="m-login__logo">
									<a href="javascript:;">
										<img src="{{asset('img/amounee-logo2.png')}}">
									</a>
								</div>
							</div>
						</div>
						<div class="m-login__border" style="padding-top:0rem;">
							<div></div>
						</div>
					</div>
				</div>
				<br>
				<br>
				<div class="m-login__wrapper-2 m-portlet-full-height" style="padding-top:0%;vertical-align:middle;">
					<div class="m-login__contanier">
						<div class="m-login__signin">
							<div class="m-login__head" style="text-align:center;">
								<h3 class="m-login__title">
									@lang('auth/frontend/heading.reset_your_password')
								</h3>
							</div>
							<form class="m-login__form m-form" style="text-align:center; padding-left:40%" action="{{route('password.attempt-reset',[$id,$code])}}" data-redirect-url="{{route('redirect')}}" name="resetPasswordForm" id="resetPasswordForm" onsubmit="return false;">
								<!-- <div class="row"> -->
									<div class="row">
										<div class="col-md-4 col-12 form-group m-form__group has-feedback">
											<input type="password"  class="form-control m-input" placeholder="@lang('auth/frontend/placeholders.new_password')" name="password" id="password" tabindex="1" autofocus>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4 col-12 form-group m-form__group has-feedback">
											<input type="password"  class="form-control m-input" placeholder="@lang('auth/frontend/placeholders.confirm_new_password')" name="password_confirmation" id="password_confirmation" tabindex="2">
										</div>
									</div>
									<div class="row">
										<div class="col-md-4 col-12 m-login__form-action">
											<button class="btn btn-brand pin-submit pin-common-submit" style="color:#ffffff;" tabindex="3">
												@lang('auth/frontend/buttons.reset_password')
											</button>
										</div>
									</div>
								<!-- </div> -->
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
        @include('layouts._include-js')
	</body>
</html>
