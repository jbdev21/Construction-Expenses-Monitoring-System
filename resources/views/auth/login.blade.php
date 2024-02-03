@extends('includes.layouts.empty')

@section('content')
<body class="app app-login p-0">    	
    <div class="row g-0 app-auth-wrapper">
	    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5 mt-3">
		    <div class="d-flex flex-column align-content-end">
			    <div class="app-auth-body mx-auto">	
				    <div class="app-auth-branding mb-4">
						<img class="logo-icon me-2 img-thumbnail mb-2" src="/images/logo.png" alt="logo" width="140">
							<h1 style="font-family:galliard;">
								<span class="logo-text text-success">WOODLAND</span>
							</h1>
						<p style="font-family:galliard;">CONSTRUCTION & SUPPLY, INC.</p>
					</div>
			        <div class="auth-form-container text-start">
						 <form method="POST" action="{{ route('login') }}">
                            @csrf      
							<div class="email mb-3">
								<label class="sr-only" for="signin-email">Email</label>
								  <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" required autocomplete="username" autofocus>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								{{-- <input id="signin-email" name="signin-email" type="email" class="form-control signin-email" placeholder="Email address" required="required"> --}}
							</div><!--//form-group-->
							<div class="password mb-3">
								<label class="sr-only" for="signin-password">Password</label>
								<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								{{-- <input id="signin-password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" required="required"> --}}
								<div class="extra mt-3 row justify-content-between">
									<div class="col-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
											<label class="form-check-label" for="RememberPassword">
											Remember me
											</label>
										</div>
									</div><!--//col-6-->
									{{-- <div class="col-6">
										<div class="forgot-password text-end">
											<a href="{{ route('password.request') }}">Forgot password?</a>
										</div>
									</div><!--//col-6--> --}}
								</div><!--//extra-->
							</div><!--//form-group-->
							<div class="text-center">
								<button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
							</div>
						</form>
						{{-- <div class="auth-option text-center pt-5">No Account? Sign up <a class="text-link" href="signup.html" >here</a>.</div> --}}
					</div>
			    </div>
			    <footer class="app-auth-footer">
					<div class="container text-center py-3">
			      	 	<small class="copyright">Designed and Developed with <i class="fa fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="https://www.facebook.com/jofie.bernas" target="_blank">Jofie Bernas</a> and Team.</small>
					</div>
			    </footer><!--//app-auth-footer-->	
		    </div><!--//flex-column-->   
	    </div><!--//auth-main-col-->
	    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
		    <div class="auth-background-holder">

		    </div>
		    <div class="auth-background-mask">

			</div>
	    </div><!--//auth-background-col-->
    
    </div><!--//row-->
</body>
@endsection
