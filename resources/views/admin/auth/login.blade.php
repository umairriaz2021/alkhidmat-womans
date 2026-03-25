@extends('admin.login_layout')
@section('title','Admin Login')
@section('content')
<div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-forms-light text-left py-5 px-4 px-sm-5 text-center">
                <div class="brand-logo">
                  <img src="{{asset('assets/images/logo-AKFP.png')}}" alt="logo">
                </div>
                <h4>Hello! let's get started</h4>
                <h6 class="fw-light">Sign in to continue.</h6>
                <form action="{{ route('admin.login.submit') }}" class="pt-3" method="POST">
                    @csrf
                  <div class="form-group">
                    <input type="email" name="email" class="form-control value="{{old('email')}}" required form-control-lg id="email" placeholder="Username">
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" required class="form-control form-control-lg" id="password" placeholder="Password">
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn" >SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" id="remember_me" name="remember" class="form-check-input"> Keep me signed in </label>
                    </div>
                     @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-black">Forgot password?</a>
                    @endif
                  </div>
                  <!-- <div class="mb-2 d-grid gap-2">
                    <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="ti-facebook me-2"></i>Connect using facebook </button>
                  </div> -->
                  <div class="text-center mt-4 fw-light"> Don't have an account? <a href="{{route('admin.register')}}" class="text-primary">Create</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
@endsection