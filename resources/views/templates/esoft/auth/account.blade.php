@extends('templates.esoft.layouts.base', ['title' => 'Account'])

@section('body_attribute')
  class="body2 body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  <!-- =====log in area start======= -->
  <div class="log-in-area sp _relative">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="main-logo">
        <a href="{{ route('any', 'index') }}"><img src="/img/logo/header-logo2.png" alt="" /></a>
      </div>
      </div>
    </div>
    <div class="space80"></div>
    <div class="row align-items-center">
      <div class="col-lg-6">
      <div class="login-form">
        <div class="headding">
        <h2>Create Account</h2>
        <p>
          Already have an account with us? <a href="{{ route('second', ['auth', 'login']) }}">Log in</a>
        </p>
        </div>

        <form action="#" class="inputs">
        <div class="row">
          <div class="col-lg-6">
          <div class="single-inputs">
            <label>Name</label>
            <input type="text" placeholder="Full name" />
          </div>
          </div>
          <div class="col-lg-6">
          <div class="single-inputs">
            <label>Email Address</label>
            <input type="email" placeholder="Enter your email" />
          </div>
          </div>
          <div class="col-lg-12">
          <div class="single-inputs">
            <label>Password</label>
            <input type="password" placeholder="Enter your password" />
          </div>
          </div>
          <div class="button">
          <button class="theme-btn2">Log In</button>
          </div>
        </div>
        </form>

        <div class="forgot-text">
        <p>
          <input type="radio" id="redio" /> <label for="redio">Don’t have an account?</label> <a class="singup" href="#">Terms & Conditions</a>
        </p>
        </div>
        <div class="brand-buttons">
        <a href="#"><img src="/img/icons/google-btn.png" alt="" /> Sign in with Google</a>
        <a href="#"><img src="/img/icons/apple-btn.png" alt="" /> Sign in with Apple</a>
        </div>
      </div>
      </div>

      <div class="col-lg-6">
      <div class="login-img">
        <img src="/img/others/login-page-img.png" alt="" />
      </div>
      </div>
    </div>
    </div>
    <img class="shape1" src="/img/shapes/apps-shape1.png" alt="" />
    <img class="shape2" src="/img/shapes/apps-shape1.png" alt="" />
  </div>
  <!-- =====log in area end======= -->
@endsection