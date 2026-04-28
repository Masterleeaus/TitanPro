@extends('templates.esoft.layouts.base', ['title' => 'HR Software Website', 'logo5' => true])

@section('body_attribute')
  class="body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  @include('templates.esoft.layouts.partials.header.navbar5')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area9">
    <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-5">
      <div class="main-heading">
        <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/logo/span9.svg" alt="" /> Keep track of your employee data</span>
        <h1 class="text-anime-style-3">Effortless Employee Management For A Growing Businesses</h1>
        <div class="space16"></div>
        <p>Managing your people is easy with Quip HR’s user-friendly and mobile-ready HR software, discover key features via.</p>
        <div class="space30"></div>
        <div class="buttons">
        <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn15">Get quip free now <span><i class="fa-solid fa-arrow-right"></i></span></a>
        </div>
      </div>
      </div>

      <div class="col-lg-7">
      <div class="main-images">
        <div class="image1">
        <img src="/img/hero/hero9-img1.png" alt="" />
        </div>
        <div class="image2 shape-animaiton3">
        <img src="/img/hero/hero9-img2.png" alt="" />
        </div>
        <div class="image3 shape-animaiton2">
        <img src="/img/hero/hero9-img3.png" alt="" />
        </div>
        <div class="image4 animate1">
        <img src="/img/hero/hero9-img4.png" alt="" />
        </div>
        <div class="image5">
        <img src="/img/hero/hero9-img5.png" alt="" />
        </div>
        <div class="main-img">
        <img src="/img/hero/hero9-main.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <div class="space100"></div>

  <!-- ===== WORK AREA START ======= -->
  <div class="work2 _relative">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="heading9">
        <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/logo/span9.svg" alt="" /> Keep track of your employee data</span>
        <h2 class="text-anime-style-3">HRMS Software Will Be Your Organization Data Warehouse</h2>
      </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-9 m-auto text-center">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab1-tab" data-bs-toggle="pill" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">1</button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab2-tab" data-bs-toggle="pill" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">2</button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab3-tab" data-bs-toggle="pill" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">3</button>
        </li>
      </ul>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
        <div class="row">
          <div class="col-lg-4">
          <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="800">
            <h3>Create Engaging Campaigns</h3>
            <img src="/img/work/work2-img1.png" alt="" />
          </div>
          </div>
          <div class="col-lg-4">
          <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="1000">
            <h3>Automate Workflows</h3>
            <img src="/img/work/work2-img2.png" alt="" />
          </div>
          </div>
          <div class="col-lg-4">
          <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="1100">
            <h3>Grow Your Reach</h3>
            <img src="/img/work/work2-img3.png" alt="" />
          </div>
          </div>
        </div>
        </div>
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
        <div class="row">
          <div class="col-lg-4">
          <div class="tabs-box-item">
            <h3>Create Engaging Campaigns</h3>
            <img src="/img/work/work2-img1.png" alt="" />
          </div>
          </div>
          <div class="col-lg-4">
          <div class="tabs-box-item">
            <h3>Automate Workflows</h3>
            <img src="/img/work/work2-img2.png" alt="" />
          </div>
          </div>
          <div class="col-lg-4">
          <div class="tabs-box-item">
            <h3>Grow Your Reach</h3>
            <img src="/img/work/work2-img3.png" alt="" />
          </div>
          </div>
        </div>
        </div>
        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
        <div class="row">
          <div class="col-lg-4">
          <div class="tabs-box-item">
            <h3>Create Engaging Campaigns</h3>
            <img src="/img/work/work2-img1.png" alt="" />
          </div>
          </div>
          <div class="col-lg-4">
          <div class="tabs-box-item">
            <h3>Automate Workflows</h3>
            <img src="/img/work/work2-img2.png" alt="" />
          </div>
          </div>
          <div class="col-lg-4">
          <div class="tabs-box-item">
            <h3>Grow Your Reach</h3>
            <img src="/img/work/work2-img3.png" alt="" />
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <img class="shape1" src="/img/shapes/home2-element1.png" alt="" />
    <img class="shape2" src="/img/shapes/home2-element2.png" alt="" />
  </div>
  <!-- ===== WORK AREA END ======= -->

  <div class="space40"></div>

  <!-- ===== COUNTERS AREA START ======= -->
  <div class="counters6">
    <div class="container">
    <div class="row">
      <div class="col-lg-3 col-6">
      <div class="counter-box box1" data-aos="zoom-out" data-aos-duration="800">
        <h2><span class="counter">16</span> M+</h2>
        <p>Built With Elementor</p>
      </div>
      </div>

      <div class="col-lg-3 col-6">
      <div class="counter-box box2" data-aos="zoom-out" data-aos-duration="1000">
        <h2><span class="counter">6.5</span> K+</h2>
        <p>5 Star Reviews</p>
      </div>
      </div>

      <div class="col-lg-3 col-6">
      <div class="counter-box box3" data-aos="zoom-out" data-aos-duration="700">
        <h2><span class="counter">100</span>+</h2>
        <p>5 Star Reviews</p>
      </div>
      </div>

      <div class="col-lg-3 col-6">
      <div class="counter-box box4" data-aos="zoom-out" data-aos-duration="1200">
        <h2><span class="counter">16</span>Sec.</h2>
        <p>15 Second An Elementor</p>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== COUNTERS AREA END ======= -->

  <div class="space100"></div>

  <!-- ===== WORK PROSESS AREA START ======= -->
  <div class="work-prosess9 sp _relative">
    <div class="container">
    <div class="row">
      <div class="col-lg-7 m-auto text-center">
      <div class="heading9">
        <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/logo/span9.svg" alt="" /> eSoft Workforce</span>
        <h2 class="text-anime-style-3">Empower Of HR Workforce</h2>
      </div>
      </div>
    </div>

    <div class="space30"></div>
    <div class="row">
      <div class="col-lg-6">
      <div class="work-box-area left-top">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="800">
        <div class="heading7">
          <h4><a href="#">Al-Powered Productivity</a></h4>
          <div class="space16"></div>
          <p>With an intuitive interface and powerful features, our platform simplifies collaboration, allowing you to effortlessly delegate.</p>
        </div>
        <div class="space40"></div>
        <div class="image">
          <img src="/img/work/work-prosses7-img1.png" alt="" />
        </div>
        </div>
      </div>
      </div>

      <div class="col-lg-6">
      <div class="work-box-area right-top">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="1000">
        <div class="heading7">
          <h4><a href="#">View Work Your Way</a></h4>
          <div class="space16"></div>
          <p>Team's project management experience. Our robust suite & offers a unified hub an task allocation, collaboration, project.</p>
        </div>
        <div class="space40"></div>
        <div class="image">
          <img src="/img/work/work-prosses7-img2.png" alt="" />
        </div>
        </div>
      </div>
      </div>

      <div class="col-lg-4">
      <div class="work-box-area right-top">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="900">
        <div class="heading7">
          <h4><a href="#">Search Anythings</a></h4>
          <div class="space16"></div>
          <p>With integrated file sharing & version control, your team a access manage</p>
        </div>
        <div class="space40"></div>
        <div class="image">
          <img src="/img/work/work-prosses7-img3.png" alt="" />
        </div>
        </div>
      </div>
      </div>

      <div class="col-lg-4">
      <div class="work-box-area left-bottom">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="1200">
        <div class="heading7">
          <h4><a href="#">Team Collaboration</a></h4>
          <div class="space16"></div>
          <p>Our platform brings together task an boards, share calendars, document.</p>
        </div>
        <div class="space40"></div>
        <div class="image">
          <img src="/img/work/work-prosses7-img4.png" alt="" />
        </div>
        </div>
      </div>
      </div>

      <div class="col-lg-4">
      <div class="work-box-area right-bottom">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="1200">
        <div class="heading7">
          <h4><a href="#">Customized In Click</a></h4>
          <div class="space16"></div>
          <p>Empower your projects with a holistic workspace solution that a simplifies.</p>
        </div>
        <div class="space40"></div>
        <div class="image">
          <img src="/img/work/work-prosses7-img5.png" alt="" />
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== WORK PROSESS AREA END ======= -->

  <div class="space100"></div>

  <!-- ===== SYSTEM AREA START ======= -->
  <div class="buy-sell-bottom2">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="heading9">
        <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/logo/span9.svg" alt="" /> eSoft Benefits</span>
        <h2 class="text-anime-style-3">eSoft HR Use Of Benefits</h2>
      </div>
      </div>
    </div>
    </div>
    <div class="container">
    <div class="bg">
      <div class="row align-items-center">
      <div class="col-lg-3">
        <div class="box-area-all" data-aos="flip-right" data-aos-duration="900">
        <div class="icon">
          <img src="/img/icons/system-icon1.svg" alt="" />
        </div>
        <div class="single-box heading7">
          <h5><a href="buy">Trade Local Currency</a></h5>
          <div class="space10"></div>
          <p>With our intuitive interface and robust features, you buy, sell.</p>
        </div>
        </div>

        <div class="space50"></div>
        <div class="box-area-all box-area-all2" data-aos="flip-right" data-aos-duration="900">
        <div class="icon">
          <img src="/img/icons/system-icon2.svg" alt="" />
        </div>
        <div class="single-box heading7">
          <h5><a href="buy">Largest Coin Offer</a></h5>
          <div class="space10"></div>
          <p>Plus, our commitment to the security means that PayCoin.</p>
        </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="service1-main-images" data-aos="zoom-out" data-aos-duration="900">
        <div class="main-img1">
          <img src="/img/others/system9-img1.png" alt="" />
        </div>

        <div class="main-img2 animate1">
          <img src="/img/others/system9-img2.png" alt="" />
        </div>

        <div class="shape1">
          <img src="/img/shapes/system-shape1.png" alt="" />
        </div>

        <div class="shape2">
          <img src="/img/shapes/system-shape2.png" alt="" />
        </div>

        <div class="shape3">
          <img src="/img/shapes/system-shape3.png" alt="" />
        </div>

        <div class="shape4">
          <img src="/img/shapes/system-shape4.png" alt="" />
        </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="box-area-all box-area-all3" data-aos="flip-right" data-aos-duration="900">
        <div class="icon">
          <img src="/img/icons/system-icon3.svg" alt="" />
        </div>
        <div class="single-box heading7">
          <h5><a href="buy">Safe And Secure</a></h5>
          <div class="space10"></div>
          <p>Trusted partner in the world of crypto trading Join us today.</p>
        </div>
        </div>

        <div class="space50"></div>
        <div class="box-area-all box-area-all4" data-aos="flip-right" data-aos-duration="900">
        <div class="icon">
          <img src="/img/icons/system-icon4.svg" alt="" />
        </div>
        <div class="single-box heading7">
          <h5><a href="buy">Trade For Anywhere</a></h5>
          <div class="space10"></div>
          <p>Resources designed to help navigate best the dynamic</p>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>

  <div class="space100"></div>
  <!-- ===== SYSTEM AREA END ======= -->

  <!-- ===== HR SOLUTION AREA START ======= -->
  <div class="hr-solutions">
    <div class="container">
    <div class="row">
      <div class="col-lg-7">
      <div class="image">
        <img src="/img/others/hr-solution-img.png" alt="" />
      </div>
      </div>
      <div class="col-lg-5">
      <div class="heading9">
        <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/logo/span9.svg" alt="" /> HR Solution</span>
        <h2 class="text-anime-style-3">Powerful Features For Effective HR Solutions</h2>
        <div class="space16"></div>
        <p data-aos="fade-left" data-aos-duration="700">Experience a new level of efficiency with our all-in-one HR software solution. Our intuitive platform is built to simplify your workflow, automating tedious tasks and providing powerful tools for seamless HR management.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-left" data-aos-duration="1000">
        <a href="#" class="theme-btn15">Get quip free now <span><i class="fa-solid fa-arrow-right"></i></span></a>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== HR SOLUTION AREA END ======= -->

  <!-- ===== TES AREA START ======= -->
  <div class="tes9 sp">
    <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 text-center m-auto">
      <div class="heading9">
        <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/logo/span9.svg" alt="" /> Testimonials</span>
        <h2 class="text-anime-style-3">Why Our Users Love Us</h2>
      </div>
      </div>
    </div>

    <div class="space60"></div>

    <div class="row">
      <div class="col-lg-12">
      <div class="tes2-slider-all" data-aos="fade-up" data-aos-duration="900">
        <div class="single-slider">
        <ul class="stars">
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
        </ul>

        <div class="icon">
          <img src="/img/icons/tes2-icon.png" alt="" />
        </div>

        <p>"I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.”</p>

        <div class="single-slider-bottom">
          <div class="headdding-area">
          <div class="image">
            <img src="/img/testimonial/tes2-img1.png" alt="" />
          </div>
          <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
          </div>
          </div>
          <div class="logo">
          <img src="/img/logo/tes2-logo1.png" alt="" />
          </div>
        </div>
        </div>

        <div class="single-slider">
        <ul class="stars">
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
        </ul>

        <div class="icon">
          <img src="/img/icons/tes2-icon.png" alt="" />
        </div>

        <p>"eSoft has exceeded our expectations in every way. The ease with which we can target specific audience segments has an transformed our approach to email marketing. The automation features have saved us countless hours, allowing us to focus”</p>

        <div class="single-slider-bottom">
          <div class="headdding-area">
          <div class="image">
            <img src="/img/testimonial/tes2-img2.png" alt="" />
          </div>
          <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
          </div>
          </div>
          <div class="logo">
          <img src="/img/logo/tes2-logo2.png" alt="" />
          </div>
        </div>
        </div>

        <div class="single-slider">
        <ul class="stars">
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
        </ul>

        <div class="icon">
          <img src="/img/icons/tes2-icon.png" alt="" />
        </div>

        <p>"I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.”</p>

        <div class="single-slider-bottom">
          <div class="headdding-area">
          <div class="image">
            <img src="/img/testimonial/tes2-img1.png" alt="" />
          </div>
          <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
          </div>
          </div>
          <div class="logo">
          <img src="/img/logo/tes2-logo1.png" alt="" />
          </div>
        </div>
        </div>

        <div class="single-slider">
        <ul class="stars">
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
          <li>
          <i class="fa-solid fa-star"></i>
          </li>
        </ul>

        <div class="icon">
          <img src="/img/icons/tes2-icon.png" alt="" />
        </div>

        <p>"eSoft has exceeded our expectations in every way. The ease with which we can target specific audience segments has an transformed our approach to email marketing. The automation features have saved us countless hours, allowing us to focus”</p>

        <div class="single-slider-bottom">
          <div class="headdding-area">
          <div class="image">
            <img src="/img/testimonial/tes2-img2.png" alt="" />
          </div>
          <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
          </div>
          </div>
          <div class="logo">
          <img src="/img/logo/tes2-logo2.png" alt="" />
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== TES AREA END ======= -->

  <!-- ===== CTA AREA END ======= -->
  <div class="cta2-area" style="background-image: url(/img/bg/cta2-bg.png); background-position: center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="headding2-w pbmit-heading-subheading animation-style2">
        <h2 class="text-anime-style-3">Join 500,000+ SEO's Who Trust eSoft For Insights That Help Their Business Grow.</h2>
        <div class="space16"></div>
        <p data-aos="fade-up" data-aos-duration="800">eSoft has exceeded our expectations in every way. The ease with & which we can target specific audience segments has an transform.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-up" data-aos-duration="1000">
        <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn3">Get Started For Free</a>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== CTA AREA END ======= -->

  <!-- ===== CTA IMAGE AREA START ======= -->
  <div class="cta2-main-image">
    <div class="container">
    <div class="row">
      <div class="col-lg-12">
      <div class="cta2-images">
        <div class="img1">
        <img src="/img/others/cta2-main-img.png" alt="" />
        </div>
        <div class="shape1">
        <img src="/img/shapes/cta2-shape2.png" alt="" />
        </div>
        <div class="shape2">
        <img src="/img/shapes/cta2-shape1.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== CTA IMAGE AREA END ======= -->

  <!-- ===== FOOTER AREA START ======= -->
  <div class="footer2 _relative">
    <div class="container">
    <div class="rwo">
      <div class="footer-icon-box-all">
      <div class="footer-icon-box">
        <div class="icon">
        <img src="/img/icons/footer-icon1.png" alt="" />
        </div>
        <div class="headding">
        <p>Free training & 24-hours</p>
        </div>
      </div>

      <div class="footer-icon-box">
        <div class="icon">
        <img src="/img/icons/footer-icon2.png" alt="" />
        </div>
        <div class="headding">
        <p>Serious about security & privacy</p>
        </div>
      </div>

      <div class="footer-icon-box">
        <div class="icon">
        <img src="/img/icons/footer-icon3.png" alt="" />
        </div>
        <div class="headding">
        <p>Highest levels of uptime the last 12 months</p>
        </div>
      </div>
      </div>
    </div>

    <div class="space50"></div>
    <div class="row">
      <div class="col-lg col-md-6 col-12">
      <div class="single-footer-items">
        <h3>Product</h3>

        <ul class="menu-list">
        <li>
          <a href="#">Pricing</a>
        </li>
        <li>
          <a href="#">Integration</a>
        </li>
        <li>
          <a href="#">Features</a>
        </li>
        <li>
          <a href="#">Templates</a>
        </li>
        <li>
          <a href="#">Changelog</a>
        </li>
        </ul>
      </div>
      </div>

      <div class="col-lg col-md-6 col-12">
      <div class="single-footer-items">
        <h3>Support</h3>

        <ul class="menu-list">
        <li>
          <a href="#">On-boarding</a>
        </li>
        <li>
          <a href="#">Help center</a>
        </li>
        <li>
          <a href="#">Contact us</a>
        </li>
        <li>
          <a href="#">Experts</a>
        </li>
        <li>
          <a href="#">Status</a>
        </li>
        </ul>
      </div>
      </div>

      <div class="col-lg col-md-6 col-12">
      <div class="single-footer-items">
        <h3>Resources</h3>

        <ul class="menu-list">
        <li>
          <a href="#">Community</a>
        </li>
        <li>
          <a href="#">Affiliates</a>
        </li>
        <li>
          <a href="#">Partnerships</a>
        </li>
        <li>
          <a href="#">Perks & Benefits</a>
        </li>
        <li>
          <a href="#">Api docs</a>
        </li>
        </ul>
      </div>
      </div>

      <div class="col-lg col-md-6 col-12">
      <div class="single-footer-items">
        <h3>Company</h3>

        <ul class="menu-list">
        <li>
          <a href="#">About</a>
        </li>
        <li>
          <a href="#">Our blog</a>
        </li>
        <li>
          <a href="#">In the press</a>
        </li>
        <li>
          <a href="#">Brand</a>
        </li>
        <li>
          <a href="#">Work with us</a>
        </li>
        </ul>
      </div>
      </div>

      <div class="col-lg col-md-6 col-12">
      <div class="single-footer-items">
        <h3>Download</h3>

        <ul class="menu-list">
        <li>
          <a href="#">iPhone & iPad</a>
        </li>
        <li>
          <a href="#">Android</a>
        </li>
        <li>
          <a href="#">MacOS</a>
        </li>
        <li>
          <a href="#">Window</a>
        </li>
        </ul>
      </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12">
      <div class="single-footer-items">
        <h3>Social Links</h3>

        <ul class="social-icons">
        <li>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        </li>
        <li>
          <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
        </li>
        <li>
          <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </li>
        <li>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </li>
        </ul>
      </div>
      </div>
    </div>

    <div class="space40"></div>

    <div class="copyright-area">
      <div class="row align-items-center">
      <div class="col-md-5">
        <div class="logo">
        <a href="{{ route('any', 'index') }}"><img src="/img/logo/header-logo2.png" alt="" /></a>
        </div>
      </div>
      <div class="col-md-7">
        <div class="coppyright text-right">
        <a href="#">@2024 eSoft</a>
        <a href="#">Security</a>
        <a href="#">Your Privacy</a>
        <a href="#">Terms</a>
        </div>
      </div>
      </div>
    </div>
    </div>

    <img class="footer-shape" src="/img/shapes/footer2-shape.png" alt="" />
    <img class="footer-shape2" src="/img/shapes/footer2-shape2.png" alt="" />
  </div>
  <!-- ===== FOOTER AREA END ======= -->
@endsection