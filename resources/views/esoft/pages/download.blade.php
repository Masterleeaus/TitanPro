@extends('esoft.layouts.landing' , [ 'title' => 'Download Page'])

@section('content')
  <!-- ===== HERO AREA START ======= -->
  <div class="pages-hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="main-headding">
            <h1>Download eSoft On Any & Every Device</h1>
            <p>
              Where email marketing meets innovation. Say goodbye to generic <br /> campaigns hello to personalised, high-converting emails resonate with your audience.
            </p>

            <div class="space30"></div>
            <div class="hero-btns">
              <a href="{{ route('esoft.second', ['auth', 'account']) }}" class="theme-btn2">Get Started Now</a>
              <a href="{{ route('esoft.second', ['auth', 'account']) }}" class="theme-btn3">Try For Free Now</a>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="hero-image">
            <img src="/templates/esoft/img/others/download-page-main.png" alt="" />
          </div>
        </div>
      </div>
    </div>
    <img class="hero-shape1" src="/templates/esoft/img/shapes/common-hero-shape1.png" alt="" />
    <img class="hero-shape2" src="/templates/esoft/img/shapes/common-hero-shape2.png" alt="" />
    <img class="hero-shape3" src="/templates/esoft/img/shapes/common-hero-shape3.png" alt="" />
  </div>
  <!-- ===== HERO AREA END ======= -->

  <!-- ===== BLOG AREA END ======= -->
  <div class="download-blog-area sp _relative">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="headding2">
            <span class="span">Apps eSoft</span>
            <h2>Apps for all your devices</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row">
        <div class="col-lg-6">
          <div class="download-box">
            <div class="image">
              <img src="/templates/esoft/img/others/download-page-img2.png" alt="" />
            </div>
            <div class="headding2">
              <span class="span">Mobile</span>
              <h5>Mobile App Version</h5>
              <div class="space10"></div>

              <div class="buttons">
                <a href="#"><img src="/templates/esoft/img/icons/apps-btn1.png" alt="" /></a>
                <a href="#"><img src="/templates/esoft/img/icons/apps-btn2.png" alt="" /></a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="download-box">
            <div class="image">
              <img src="/templates/esoft/img/others/download-page-img1.png" alt="" />
            </div>
            <div class="headding2">
              <span class="span">Tablet</span>
              <h5>Tablet Version</h5>
              <div class="space10"></div>

              <div class="buttons">
                <a href="#"><img src="/templates/esoft/img/icons/apps-btn1.png" alt="" /></a>
                <a href="#"><img src="/templates/esoft/img/icons/apps-btn2.png" alt="" /></a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="download-box">
            <div class="row align-items-center bg">
              <div class="col-lg-6">
                <div class="image">
                  <img src="/templates/esoft/img/others/download-page-img1.png" alt="" />
                </div>
              </div>
              <div class="col-lg-6">
                <div class="headding2">
                  <span class="span">Desktop eSoft</span>
                  <h5>eSoft Desktop Version</h5>
                  <div class="space10"></div>
                  <div class="buttons">
                    <a href="#"><img src="/templates/esoft/img/icons/apps-btn1.png" alt="" /></a>
                    <a href="#"><img src="/templates/esoft/img/icons/apps-btn2.png" alt="" /></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <img class="shape1" src="/templates/esoft/img/shapes/apps-shape1.png" alt="" />
    <img class="shape2" src="/templates/esoft/img/shapes/apps-shape2.png" alt="" />
  </div>
  <!-- ===== BLOG AREA END ======= -->
@endsection
