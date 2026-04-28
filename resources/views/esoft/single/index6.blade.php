@extends('esoft.layouts.base', ['title' => 'Email Marketing Sass Landing Page'])

@section('body_attribute')
  class="body2 body"
@endsection

@section('content')
  @include('esoft.layouts.partials.loader')

  @include('esoft.layouts.partials.header-single.navbar6')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area2">
    <div class="container">
    <div class="row align-items-center hero2-content">
      <div class="col-lg-6">
      <div class="main-headding pbmit-heading-subheading animation-style2">
        <h1 class="pbmit-title">Ai Powered Email Deliverability & Reputation Tool</h1>
        <p data-aos="fade-right" data-aos-duration="800">Revolutionize your email marketing strategy with cutting-edge and software designed to elevate your campaigns. Our intuitive platform is offers a seamless interface, empowering software.</p>

        <div class="space30"></div>
        <div class="hero-btns" data-aos="fade-right" data-aos-duration="1100">
        <a href="{{ route('esoft.second', ['auth', 'login']) }}" class="theme-btn2">Send Smarter Emails</a>
        <a href="{{ route('esoft.second', ['pages', 'features']) }}" class="theme-btn3">Explore Features</a>
        </div>
      </div>
      </div>

      <div class="col-lg-6">
      <div class="hero2-image">
        <div class="image1 animate1">
        <img src="/templates/esoft/img/hero/hero2-image1.png" alt="" />
        </div>
        <div class="image2">
        <img src="/templates/esoft/img/hero/hero2-image2.png" alt="" />
        </div>
        <div class="image3">
        <img src="/templates/esoft/img/hero/hero2-image3.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <!-- ===== HERO IMAGE AREA START ======= -->
  <div class="hero2-bashbord">
    <div class="container">
    <div class="row">
      <div class="col-lg-12">
      <div class="images">
        <div class="image1">
        <img src="/templates/esoft/img/hero/hero2-dasbord-img.png" alt="" />
        </div>
        <div class="image2">
        <img src="/templates/esoft/img/shapes/hero2-shape.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
    <img class="shape1" src="/templates/esoft/img/shapes/home2-element1.png" alt="" />
    <img class="shape2" src="/templates/esoft/img/shapes/home2-element2.png" alt="" />
  </div>
  <!-- ===== HERO IMAGE AREA END ======= -->

  <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0">
    <!-- ===== CHOOSE AREA START ======= -->
    <div class="choose2 sp" id="choose">
    <div class="container">
      <div class="row">
      <div class="col-lg-7 m-auto text-center">
        <div class="headding2 pbmit-heading-subheading animation-style2">
        <span class="span">Why Choose eSoft?</span>
        <h2 class="pbmit-title">Empower Brand: Transform Through Email Campaigns</h2>
        </div>
      </div>
      </div>

      <div class="space30"></div>
      <div class="row align-items-center">
      <div class="col-lg-5">
        <div class="accordion" id="accordionExample">
        <div class="accordion-item" data-aos="fade-right" data-aos-duration="800">
          <h2 class="accordion-header" id="headingOne"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Prepare your domain inbox</button></h2>
          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
          <div class="accordion-body">Experience peace mind with our deliver ability-focused product, eeSoftped & fail- an, real-time google mail checked notifications.</div>
          </div>
        </div>
        <div class="accordion-item" data-aos="fade-right" data-aos-duration="1000">
          <h2 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Email health check-up</button></h2>
          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
          <div class="accordion-body">Experience peace mind with our deliver ability-focused product, eeSoftped & fail- an, real-time google mail checked notifications.</div>
          </div>
        </div>
        <div class="accordion-item" data-aos="fade-right" data-aos-duration="1200">
          <h2 class="accordion-header" id="headingThree"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">How to write a cold email replies</button></h2>
          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
          <div class="accordion-body">Experience peace mind with our deliver ability-focused product, eeSoftped & fail- an, real-time google mail checked notifications.</div>
          </div>
        </div>
        </div>

        <div class="space30"></div>

        <div class="" data-aos="fade-right" data-aos-duration="900">
        <a href="{{ route('esoft.second', ['pages', 'features']) }}" class="theme-btn2">Boost Campaigns Now</a>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="choose-images" data-aos="zoom-in-up" data-aos-duration="900">
        <div class="image1">
          <img src="/templates/esoft/img/choose/choose2-img.png" alt="" />
        </div>
        <div class="image2">
          <img src="/templates/esoft/img/shapes/hero2-shape.png" alt="" />
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== CHOOSE AREA END ======= -->

    <!-- ===== EMAIL INNOVATION START ======= -->
    <div class="email-innovation" id="email" style="background-image: url(/templates/esoft/img/bg/others2-bg.png); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
      <div class="row">
      <div class="col-lg-5">
        <div class="headding2-w pbmit-heading-subheading animation-style2">
        <h2 class="pbmit-title">Email innovation: Craft marketing an success</h2>
        <div class="space16"></div>
        <p data-aos="fade-right" data-aos-duration="800">Email marketing journey transforms into a streamlined, an powerful experience. Our cutting-edge platform eeSofts you with the tools to craft compelling, personalized</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-right" data-aos-duration="1000">
          <a href="{{ route('esoft.second', ['auth', 'account']) }}" class="theme-btn3">Start For Free Now</a>
        </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="images" data-aos="zoom-in-up" data-aos-duration="1000">
        <div class="image1">
          <img src="/templates/esoft/img/others/others2-image.png" alt="" />
        </div>
        <div class="image2">
          <img src="/templates/esoft/img/shapes/others2-shape1.png" alt="" />
        </div>
        <div class="image3">
          <img src="/templates/esoft/img/shapes/others2-shape2.png" alt="" />
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== EMAIL INNOVATION END ======= -->

    <!-- ===== WORK AREA START ======= -->
    <div class="work2 sp _relative" id="work">
    <div class="container">
      <div class="row">
      <div class="col-lg-6 m-auto text-center">
        <div class="headding2 pbmit-heading-subheading animation-style2">
        <span class="span">How It Works</span>
        <h2 class="pbmit-title">Next-Level Email Strategies</h2>
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
            <img src="/templates/esoft/img/work/work2-img1.png" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="1000">
            <h3>Automate Workflows</h3>
            <img src="/templates/esoft/img/work/work2-img2.png" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="1100">
            <h3>Grow Your Reach</h3>
            <img src="/templates/esoft/img/work/work2-img3.png" alt="" />
            </div>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
          <div class="row">
          <div class="col-lg-4">
            <div class="tabs-box-item">
            <h3>Create Engaging Campaigns</h3>
            <img src="/templates/esoft/img/work/work2-img1.png" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tabs-box-item">
            <h3>Automate Workflows</h3>
            <img src="/templates/esoft/img/work/work2-img2.png" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tabs-box-item">
            <h3>Grow Your Reach</h3>
            <img src="/templates/esoft/img/work/work2-img3.png" alt="" />
            </div>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
          <div class="row">
          <div class="col-lg-4">
            <div class="tabs-box-item">
            <h3>Create Engaging Campaigns</h3>
            <img src="/templates/esoft/img/work/work2-img1.png" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tabs-box-item">
            <h3>Automate Workflows</h3>
            <img src="/templates/esoft/img/work/work2-img2.png" alt="" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tabs-box-item">
            <h3>Grow Your Reach</h3>
            <img src="/templates/esoft/img/work/work2-img3.png" alt="" />
            </div>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    <img class="shape1" src="/templates/esoft/img/shapes/home2-element1.png" alt="" />
    <img class="shape2" src="/templates/esoft/img/shapes/home2-element2.png" alt="" />
    </div>
    <!-- ===== WORK AREA END ======= -->

    <!-- ===== STAPES AREA START ======= -->
    <div class="stapes sp" id="stapes">
    <div class="container">
      <div class="row align-items-center">
      <div class="col-lg-5">
        <div class="headding2 pbmit-heading-subheading animation-style2">
        <span class="span">Email Deliverability</span>
        <h5 class="pbmit-title">Enhance cold email and inbox delivery</h5>
        <div class="space16"></div>
        <p data-aos="fade-right" data-aos-duration="800">Experience peace of mind with our deliverability-focused product, eeSoftped & fail-safes, real-time notifications, and spam prevention, ensuring your emails consistently land in the right inboxes and sparing you the frustration of spam placement.</p>
        <div class="space24"></div>
        <div class="" data-aos="fade-right" data-aos-duration="1000">
          <a href="{{ route('esoft.second', ['auth', 'account']) }}" class="theme-btn2">Get Started Now</a>
        </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="stapes-images1" data-aos="flip-right" data-aos-duration="900">
        <div class="image1">
          <img src="/templates/esoft/img/work/staps-1.png" alt="" />
        </div>
        <div class="image2">
          <img src="/templates/esoft/img/shapes/hero2-shape.png" alt="" />
        </div>
        </div>
      </div>
      </div>

      <div class="space120"></div>
      <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="stapes-images2">
        <div class="image1" data-aos="flip-left" data-aos-duration="900">
          <img src="/templates/esoft/img/work/staps-1.png" alt="" />
        </div>
        <div class="image2">
          <img src="/templates/esoft/img/shapes/hero2-shape.png" alt="" />
        </div>

        <div class="main-shape">
          <img src="/templates/esoft/img/shapes/staps-shape.png" alt="" />
        </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="headding2 pbmit-heading-subheading animation-style2">
        <span class="span">Email Deliverability</span>
        <h5 class="pbmit-title">Enhance cold email and inbox delivery</h5>
        <div class="space16"></div>
        <p data-aos="fade-left" data-aos-duration="800">Experience peace of mind with our deliverability-focused product, eeSoftped & fail-safes, real-time notifications, and spam prevention, ensuring your emails consistently land in the right inboxes and sparing you the frustration of spam placement.</p>
        <div class="space24"></div>
        <div class="" data-aos="fade-left" data-aos-duration="1000">
          <a href="{{ route('esoft.second', ['auth', 'account']) }}" class="theme-btn2">Get Started Now</a>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== STAPES AREA END ======= -->

    <!-- ===== APPS AREA SREA ======= -->
    <div class="apps" style="background-image: url(/templates/esoft/img/bg/apps-area-bg.png); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
      <div class="row align-items-center">
      <div class="col-lg-4">
        <div class="apps-img1" data-aos="fade-down" data-aos-duration="800">
        <img src="/templates/esoft/img/others/apps-img1.png" alt="" />
        </div>
      </div>

      <div class="col-lg-4">
        <div class="headding2-w text-center pbmit-heading-subheading animation-style2">
        <span class="span">Integrations</span>
        <h2 class="pbmit-title">Connect With Your Favorite Apps</h2>
        <div class="space16"></div>
        <p data-aos="fade-up" data-aos-duration="800">With our intuitive platform, creating impactful campaigns a breeze. Effortlessly email market</p>

        <div class="space30"></div>
        <div class="" data-aos="fade-up" data-aos-duration="1000">
          <a href="{{ route('esoft.second', ['pages', 'features']) }}" class="others">View All Integrations</a>
        </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="apps-img2" data-aos="fade-down" data-aos-duration="900">
        <img src="/templates/esoft/img/others/apps-img2.png" alt="" />
        </div>
      </div>
      </div>

      <div class="row">
      <div class="col-lg-10 m-auto text-center">
        <div class="bottom-logo">
        <img src="/templates/esoft/img/logo/others2-bottom-logo.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== APPS AREA END ======= -->

    <!-- ===== TES AREA START ======= -->
    <div class="tes2" id="testimonial">
    <div class="container">
      <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="headding2 pbmit-heading-subheading animation-style2">
        <span class="span">Testimonials</span>
        <h2 class="pbmit-title">Why Our Users Love Us</h2>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="testimonial-arrows" data-aos="fade-left" data-aos-duration="800">
        <button class="testimonial-prev-arrow2"><i class="fa-regular fa-arrow-left"></i></button>
        <button class="testimonial-next-arrow2"><i class="fa-regular fa-arrow-right"></i></button>
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
          <img src="/templates/esoft/img/icons/tes2-icon.png" alt="" />
          </div>

          <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

          <div class="single-slider-bottom">
          <div class="headdding-area">
            <div class="image">
            <img src="/templates/esoft/img/testimonial/tes2-img1.png" alt="" />
            </div>
            <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
            </div>
          </div>
          <div class="logo">
            <img src="/templates/esoft/img/logo/tes2-logo1.png" alt="" />
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
          <img src="/templates/esoft/img/icons/tes2-icon.png" alt="" />
          </div>

          <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

          <div class="single-slider-bottom">
          <div class="headdding-area">
            <div class="image">
            <img src="/templates/esoft/img/testimonial/tes2-img2.png" alt="" />
            </div>
            <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
            </div>
          </div>
          <div class="logo">
            <img src="/templates/esoft/img/logo/tes2-logo2.png" alt="" />
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
          <img src="/templates/esoft/img/icons/tes2-icon.png" alt="" />
          </div>

          <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

          <div class="single-slider-bottom">
          <div class="headdding-area">
            <div class="image">
            <img src="/templates/esoft/img/testimonial/tes2-img1.png" alt="" />
            </div>
            <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
            </div>
          </div>
          <div class="logo">
            <img src="/templates/esoft/img/logo/tes2-logo1.png" alt="" />
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
          <img src="/templates/esoft/img/icons/tes2-icon.png" alt="" />
          </div>

          <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

          <div class="single-slider-bottom">
          <div class="headdding-area">
            <div class="image">
            <img src="/templates/esoft/img/testimonial/tes2-img2.png" alt="" />
            </div>
            <div class="headding">
            <h5><a href="#">Pat Cummins</a></h5>
            <p>Ceo Biosynthesis</p>
            </div>
          </div>
          <div class="logo">
            <img src="/templates/esoft/img/logo/tes2-logo2.png" alt="" />
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>

    <img class="tes2-shape" src="/templates/esoft/img/shapes/footer2-shape2.png" alt="" />
    </div>
    <!-- ===== TES AREA END ======= -->
  </div>

  <!-- ===== BLOG AREA START ======= -->
  <div class="blog2 sp">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="headding2 pbmit-heading-subheading animation-style2">
        <span class="span">Our Blog</span>
        <h2 class="pbmit-title">Our Latest Blog & News</h2>
      </div>
      </div>
    </div>

    <div class="space30"></div>
    <div class="row">
      <div class="col-lg-6">
      <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="800">
        <div class="image">
        <img src="/templates/esoft/img/blog/blog2-img1.png" alt="" />
        </div>
        <div class="headding">
        <div class="tags">
          <a href="#"><img src="/templates/esoft/img/icons/blog2-icon1.png" alt="" /> 10 October 2023</a>
          <a href="#"><img src="/templates/esoft/img/icons/blog2-icon2.png" alt="" /> Rabby Mahmud</a>
        </div>
        <h4><a href="{{ route('esoft.second', ['blogs', 'details']) }}">The Ultimate Email Campaign Playbook</a></h4>
        <p>Effortlessly design stunning emails, automate your market workflow, & precisely target your audience for maximum.</p>
        <a href="{{ route('esoft.second', ['blogs', 'details']) }}" class="learn">Read more <span><i class="fa-regular fa-arrow-right"></i></span></a>
        </div>
      </div>
      </div>

      <div class="col-lg-6">
      <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="1000">
        <div class="image">
        <img src="/templates/esoft/img/blog/blog2-img2.png" alt="" />
        </div>
        <div class="headding">
        <div class="tags">
          <a href="#"><img src="/templates/esoft/img/icons/blog2-icon1.png" alt="" /> 10 October 2023</a>
          <a href="#"><img src="/templates/esoft/img/icons/blog2-icon2.png" alt="" /> Rabby Mahmud</a>
        </div>
        <h4><a href="{{ route('esoft.second', ['blogs', 'details']) }}">Email Design: A Deep Dive in Visual Impact</a></h4>
        <p>Effortlessly design stunning emails, automate your market workflow, & precisely target your audience for maximum.</p>
        <a href="{{ route('esoft.second', ['blogs', 'details']) }}" class="learn">Read more <span><i class="fa-regular fa-arrow-right"></i></span></a>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== BLOG AREA END ======= -->

  @include('esoft.layouts.partials.cta')

  @include('esoft.layouts.partials.footer')
@endsection