@extends('templates.esoft.layouts.base', ['title' => 'Successfully Done!'])

@section('body_attribute')
  class="body2 body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  <!-- =====log in area start======= -->
  <div class="log-in-area Success_relative" style="position: relative; margin-bottom: -230px; z-index: 2;">
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
      <div class="col-lg-6 m-auto">
      <div class="login-form">
        <div class="Success-icon d-flex align-items-center justify-content-center">
        <img src="/img/icons/successe-icon.png" alt="" />
        </div>
        <div class="space20"></div>
        <div class="headding">
        <h2>Successfully Done!</h2>
        <div class="space30"></div>
        <div class="button">
          <a href="{{ route('any', 'index') }}" class="theme-btn2">Go Back Home Page</a>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <img class="shape1" src="/img/shapes/apps-shape1.png" alt="" />
    <img class="shape2" src="/img/shapes/apps-shape1.png" alt="" />
  </div>

  <div class="bg-area-all" style="background-image: url(/img/bg/fotgot-bg.png); background-position: center; background-repeat:  no-repeat; background-size: cover; min-height: 390px;"></div>
  <!-- =====log in area end======= -->
@endsection