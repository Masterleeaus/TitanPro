@extends('esoft.layouts.base', ['title' => 'Login'])

@section('body_attribute')
  class="body2 body"
@endsection

@section('content')
  @include('esoft.layouts.partials.loader')

  <!-- =====log in area start======= -->
  <div class="log-in-area sp _relative">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="main-logo">
        <a href="{{ route('esoft.any', 'index') }}"><img src="/templates/esoft/img/logo/header-logo2.png" alt="" /></a>
      </div>
      </div>
    </div>
    <div class="space80"></div>
    <div class="row align-items-center">
      <div class="col-lg-6">
      <div class="login-form">
        <div class="headding">
        <h2>Log In</h2>
        <p>Please fill your email and password to sign in.</p>
        </div>

        <form action="#" class="inputs">
        <div class="single-inputs">
          <label>Email Address</label>
          <input type="text" placeholder="Enter your email" />
        </div>
        <div class="single-inputs">
          <label>Password</label>
          <input type="password" placeholder="Enter your password" />
        </div>
        <div class="button">
          <button class="theme-btn2">Log In</button>
        </div>
        </form>

        <div class="forgot-text">
        <p>
          Don’t have an account? <a class="singup" href="{{ route('esoft.second', ['auth', 'account']) }}">Sign up today</a> <br /> <a href="{{ route('esoft.second', ['auth', 'forgot']) }}">Forget password</a>
        </p>
        </div>
        <div class="brand-buttons">
        <a href="#"><img src="/templates/esoft/img/icons/google-btn.png" alt="" /> Sign in with Google</a>
        <a href="#"><img src="/templates/esoft/img/icons/apple-btn.png" alt="" /> Sign in with Apple</a>
        </div>
      </div>
      </div>

      <div class="col-lg-6">
      <div class="login-img">
        <img src="/templates/esoft/img/others/login-page-img.png" alt="" />
      </div>
      </div>
    </div>
    </div>
    <img class="shape1" src="/templates/esoft/img/shapes/apps-shape1.png" alt="" />
    <img class="shape2" src="/templates/esoft/img/shapes/apps-shape1.png" alt="" />
  </div>
  <!-- =====log in area end======= -->
@endsection