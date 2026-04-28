@extends('templates.esoft.layouts.base' , [ 'title' => '404'])

@section('body_attribute')
  class="body2 body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  <!-- =====log in area start======= -->
  <div class="error-all sp _relative">
    <div class="container">
      <div class="space80"></div>
      <div class="row align-items-center">
        <div class="col-lg-8 m-auto">
          <div class="erro-page">
            <div class="image">
              <img src="/img/others/error.png" alt="" />
            </div>
            <div class="headding2 text-center">
              <h2>Sorry, Something Went Wrong</h2>
              <div class="space16"></div>
              <p>But don't worry, we've got your back. Here are a few options to get you back on track: Contact Support: If you believe there's a problem with our site.</p>
              <div class="space30"></div>
              <a href="{{ route('any', 'index') }}" class="theme-btn2">Go Back To Home Page</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <img class="shape1" src="/img/shapes/apps-shape1.png" alt="" />
    <img class="shape2" src="/img/shapes/apps-shape1.png" alt="" />
  </div>
  <!-- =====log in area end======= -->
@endsection
