@extends('esoft.layouts.base', ['title' => 'Forgot Password'])

@section('body_attribute')
  class="body2 body"
@endsection

@section('content')
  @include('esoft.layouts.partials.loader')

  <!-- =====log in area start======= -->
  <div class="log-in-area forgot-area _relative">
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
      <div class="col-lg-6 m-auto">
      <div class="login-form">
        <div class="headding">
        <h2>Forgot Password?</h2>
        <p>If you forgot your password, please enter your email below and we will send you a recovery link.</p>
        </div>

        <form action="#" class="inputs">
        <div class="single-inputs">
          <label>Email Address</label>
          <input type="text" placeholder="Enter your email" />
        </div>
        <div class="button">
          <button class="theme-btn2">Send Recovery Link</button>
        </div>
        </form>

        <div class="forgot-text">
        <p>
          Remember your password <a class="singup" href="{{ route('esoft.second', ['auth', 'login']) }}">Log In</a>
        </p>
        </div>
      </div>
      </div>
    </div>
    </div>
    <img class="shape1" src="/templates/esoft/img/shapes/apps-shape1.png" alt="" />
    <img class="shape2" src="/templates/esoft/img/shapes/apps-shape1.png" alt="" />
  </div>

  <div class="bg-area-all" style="background-image: url(/templates/esoft/img/bg/fotgot-bg.png); background-position: center; background-repeat:  no-repeat; background-size: cover; min-height: 390px;"></div>
  <!-- =====log in area end======= -->
@endsection