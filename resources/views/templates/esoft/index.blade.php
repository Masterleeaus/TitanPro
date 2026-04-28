@extends('templates.esoft.layouts.base', ['title' => 'Web Page Builder', 'logo5' => true])

@section('body_attribute')
  class="body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  @include('templates.esoft.layouts.partials.header.navbar')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area6">
    <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-5">
      <div class="main-heading">
        <span class="span" data-aos="fade-left" data-aos-duration="700"><img src="/img/icons/main-span6.png" alt="" /> Top Choice For 5,800+ Websites Worldwide</span>
        <h1 class="text-anime-style-3">Build Your Website With eSoft Builder</h1>
        <div class="space16"></div>
        <p data-aos="fade-right" data-aos-duration="700">eSoft takes the Customizer to the next level so you can customize every aspect your website single interface.</p>
        <div class="space30"></div>
        <div class="buttons" data-aos="fade-right" data-aos-duration="900">
        <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn8"><span class="tb8">Get Started Now <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        <div class="video-action-btn1 popup-youtube">
          <div class="">
          <a id="play-video" class="video-play-button play-btn" href="https://www.youtube.com/watch?v=Y8XpQpW5OVY"><span></span></a>
          </div>
          <div class="video-btn-pera">
          <p>Play Video</p>
          </div>
        </div>
        </div>
      </div>
      </div>

      <div class="col-lg-7">
      <div class="images-all" data-aos="zoom-out" data-aos-duration="800">
        <div class="image1">
        <img src="/img/hero/hero6-img1.png" alt="" />
        </div>
        <div class="image2 animate1">
        <img src="/img/hero/hero6-img2.png" alt="" />
        </div>
        <div class="image3 shape-animaiton2">
        <img src="/img/hero/hero6-img3.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <!-- ===== COUNTERS AREA START ======= -->
  <div class="counters6 sp">
    <div class="container">
    <div class="row">
      <div class="col-lg-12 m-auto text-center">
      <div class="heading6">
        <h3 class="text-anime-style-3">Trusted by Top Choice For 5,800+ Websites Worldwide</h3>
      </div>
      </div>
    </div>
    <div class="space30"></div>
    <div class="row">
      <div class="col-lg-3 col-6">
      <div class="counter-box box1" data-aos="zoom-out" data-aos-duration="700">
        <h2><span class="counter">16</span> M+</h2>
        <p>Built With Elementor</p>
      </div>
      </div>

      <div class="col-lg-3 col-6">
      <div class="counter-box box2" data-aos="zoom-out" data-aos-duration="900">
        <h2><span class="counter">6.5</span> K+</h2>
        <p>5 Star Reviews</p>
      </div>
      </div>

      <div class="col-lg-3 col-6">
      <div class="counter-box box3" data-aos="zoom-out" data-aos-duration="1100">
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

  <!-- ===== EDITING AREA START ======= -->
  <div class="editing6 sp">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="heading6">
        <span class="title"><img src="/img/icons/span-image6.png" alt="" /></span>
        <h2 class="text-anime-style-3">Powerful Visual Editing For Your Entire Website</h2>
      </div>
      </div>
    </div>

    <div class="space30"></div>
    <div class="row">
      <div class="col-lg-4 col-md-6" data-aos="zoom-in-up" data-aos-duration="600">
      <div class="editing-box">
        <div class="icon">
        <img src="/img/icons/editing6-icon1.svg" alt="" />
        </div>
        <div class="space16"></div>
        <div class="heading6">
        <h4><a href="{{ route('second', ['pages', 'features']) }}">Drag & Drop Editing</a></h4>
        <div class="space16"></div>
        <p>Drag a "Text Editor" widget from the sidebar and drop it into the desired section or column on your page.</p>
        </div>
      </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="zoom-in-up" data-aos-duration="900">
      <div class="editing-box">
        <div class="icon">
        <img src="/img/icons/editing6-icon2.svg" alt="" />
        </div>
        <div class="space16"></div>
        <div class="heading6">
        <h4><a href="{{ route('second', ['pages', 'features']) }}">True Visual Editing</a></h4>
        <div class="space16"></div>
        <p>Design your page in real time and see the results instantly. Create an customize your all landing pages.</p>
        </div>
      </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="zoom-in-up" data-aos-duration="1100">
      <div class="editing-box">
        <div class="icon">
        <img src="/img/icons/editing6-icon3.svg" alt="" />
        </div>
        <div class="space16"></div>
        <div class="heading6">
        <h4><a href="{{ route('second', ['pages', 'features']) }}">Custom CSS Control</a></h4>
        <div class="space16"></div>
        <p>Developers can easily combine Divi's visual design controls with their own custom CSS, eSoft interface is best.</p>
        </div>
      </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="zoom-in-up" data-aos-duration="700">
      <div class="editing-box">
        <div class="icon">
        <img src="/img/icons/editing6-icon4.svg" alt="" />
        </div>
        <div class="space16"></div>
        <div class="heading6">
        <h4><a href="{{ route('second', ['pages', 'features']) }}">Responsive Editing</a></h4>
        <div class="space16"></div>
        <p>eSoft Building beautiful responsive websites is easy. Divi is responsive by nature & also gives full control.</p>
        </div>
      </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="zoom-in-up" data-aos-duration="1100">
      <div class="editing-box">
        <div class="icon">
        <img src="/img/icons/editing6-icon5.svg" alt="" />
        </div>
        <div class="space16"></div>
        <div class="heading6">
        <h4><a href="{{ route('second', ['pages', 'features']) }}">Copy/Paste Design</a></h4>
        <div class="space16"></div>
        <p>eSoft Save and manage unlimited custom designs. Easily re-use them to jump-start new home pages.</p>
        </div>
      </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="zoom-in-up" data-aos-duration="800">
      <div class="editing-box">
        <div class="icon">
        <img src="/img/icons/editing6-icon6.svg" alt="" />
        </div>
        <div class="space16"></div>
        <div class="heading6">
        <h4><a href="{{ route('second', ['pages', 'features']) }}">Global Element Styles</a></h4>
        <div class="space16"></div>
        <p>Manage your entire website's design using global elements and website-wide design settings.</p>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== EDITING AREA END ======= -->

  <!-- ===== FEATURES AREA START ======= -->
  <div class="features6 sp">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="heading6">
        <span class="title"><img src="/img/icons/span-image6.png" alt="" /></span>
        <h2 class="text-anime-style-3">eSoft Builder Features</h2>
      </div>
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-lg-6">
      <div class="main-image right60" data-aos="zoom-out" data-aos-duration="800">
        <img src="/img/others/features6-img1.png" alt="" />
      </div>
      </div>
      <div class="col-1"></div>
      <div class="col-lg-5">
      <div class="heading6 features-heading">
        <span class="span3" data-aos="fade-left" data-aos-duration="700">Page Builder</span>
        <h3 class="text-anime-style-3">Front/Back End Page Builder</h3>
        <div class="space16"></div>
        <p data-aos="fade-left" data-aos-duration="900">Build a responsive website and manage your content easily with intuitive WordPress Front end editor. No programming knowledge required – create stunning and beautiful pages with award-winning drag and drop builder.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-left" data-aos-duration="900">
        <a href="{{ route('second', ['pages', 'features']) }}" class="theme-btn9"><span class="tb8">Get More Info <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        </div>
      </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-5">
      <div class="heading6 features-heading">
        <span class="span3" data-aos="fade-right" data-aos-duration="700">Any Color Choose</span>
        <h3 class="text-anime-style-3">Roll Your Own Colour Theme</h3>
        <div class="space16"></div>
        <p data-aos="fade-right" data-aos-duration="900">Built-in Skin Builder: Use the built-in skin builder to tweak eSoft Page Builder design options and elements styling, making a perfect match with your brand identity.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-right" data-aos-duration="1100">
        <a href="{{ route('second', ['pages', 'features']) }}" class="theme-btn9"><span class="tb8">Get More Info <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        </div>
      </div>
      </div>
      <div class="col-1"></div>
      <div class="col-lg-6">
      <div class="main-image right60" data-aos="zoom-out" data-aos-duration="1000">
        <img src="/img/others/features6-img2.png" alt="" />
      </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-6">
      <div class="main-image right60" data-aos="zoom-out" data-aos-duration="1100">
        <img src="/img/others/features6-img3.png" alt="" />
      </div>
      </div>
      <div class="col-1"></div>
      <div class="col-lg-5">
      <div class="heading6 features-heading">
        <span class="span3" data-aos="fade-left" data-aos-duration="700">Any Theme Design</span>
        <h3 class="text-anime-style-3">For Any WordPress Theme</h3>
        <div class="space16"></div>
        <p data-aos="fade-left" data-aos-duration="900">Every website design needs to be unique. With WPBakery Page Builder, you can work with any WordPress theme of your choice. This powerful tool allows you to create responsive an stunning layouts without any coding knowledge.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-left" data-aos-duration="1000">
        <a href="{{ route('second', ['pages', 'features']) }}" class="theme-btn9"><span class="tb8">Get More Info <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        </div>
      </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-5">
      <div class="heading6 features-heading">
        <span class="span3" data-aos="fade-right" data-aos-duration="700">Template Library</span>
        <h3 class="text-anime-style-3">eSoft Builder Template Library</h3>
        <div class="space16"></div>
        <p data-aos="fade-right" data-aos-duration="900">Access premium class templates via the online Template Library and build your pages in seconds. Download any template you like without any restrictions. Template Library gets constantly updated with new templates.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-right" data-aos-duration="1000">
        <a href="{{ route('second', ['pages', 'features']) }}" class="theme-btn9"><span class="tb8">Get More Info <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        </div>
      </div>
      </div>
      <div class="col-1"></div>
      <div class="col-lg-6">
      <div class="main-image right60" data-aos="zoom-out" data-aos-duration="800">
        <img src="/img/others/features6-img4.png" alt="" />
      </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-6">
      <div class="main-image right60" data-aos="zoom-out" data-aos-duration="1000">
        <img src="/img/others/features6-img5.png" alt="" />
      </div>
      </div>
      <div class="col-1"></div>
      <div class="col-lg-5">
      <div class="heading6 features-heading">
        <span class="span3" data-aos="fade-left" data-aos-duration="700">any layout available</span>
        <h3 class="text-anime-style-3">Any Mobile/Laptop Layouts</h3>
        <div class="space16"></div>
        <p data-aos="fade-left" data-aos-duration="900">WPBakery Page Builder ensures your website looks perfect on every device, whether it’s a mobile phone, tablet, laptop. The responsive design capabilities allow you create layouts.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-left" data-aos-duration="1000">
        <a href="{{ route('second', ['pages', 'features']) }}" class="theme-btn9"><span class="tb8">Get More Info <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== FEATURES AREA END ======= -->

  <!-- ===== BRANDS AREA START ======= -->
  <div class="brands-area6 sp">
    <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-5">
      <div class="heading6-w">
        <span class="span2" data-aos="fade-left" data-aos-duration="700">Integration brands</span>
        <h2 class="text-anime-style-3">Integrate Your eSoft Builder With 3K App</h2>
        <div class="space16"></div>
        <p data-aos="fade-left" data-aos-duration="900">Seamlessly integrate your eSoft Builder with the 3K App to elevate your workflow and enhance productivity. By combining the powerful design capabilities of eSoft Builder with the robust features of the 3K Apps.</p>
        <div class="space30"></div>
        <div class="" data-aos="fade-left" data-aos-duration="1000">
        <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn8"><span class="tb8">Get Started, It’s Free <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
        </div>
      </div>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-6">
      <div class="apps-images">
        <div class="image1">
        <img src="/img/bg/apps5-shape-bg.png" alt="" />
        <div class="dot"></div>
        </div>
        <div class="image2">
        <img src="/img/others/apps5.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== BRANDS AREA END ======= -->

  <!-- ===== FAQ AREA START ======= -->
  <div class="faq6 sp">
    <div class="container">
    <div class="row">
      <div class="col-lg-10 m-auto text-center">
      <div class="heading6">
        <span class="span">FAQ Question</span>
        <h2 class="text-anime-style-3">Frequently Asked Question</h2>
      </div>

      <div class="space40"></div>

      <div class="accordion accordion1" id="accordionExample">
        <div class="accordion-item active" data-aos="fade-up" data-aos-duration="700">
        <h2 class="accordion-header" id="headingOne"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">What is blockchain technology</button></h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
          <div class="accordion-body">It's very simple! Register here. In your personal account, create wallet where you can store your FOX tokens. Then just send any amount to the displayed address in the your office.</div>
        </div>
        </div>
        <div class="accordion-item" data-aos="fade-up" data-aos-duration="900">
        <h2 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">what is the value of fOX tokens?</button></h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
          <div class="accordion-body">It's very simple! Register here. In your personal account, create wallet where you can store your FOX tokens. Then just send any amount to the displayed address in the your office.</div>
        </div>
        </div>
        <div class="accordion-item" data-aos="fade-up" data-aos-duration="1100">
        <h2 class="accordion-header" id="headingThree"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">I use cryptocurrency participate in the ICO?</button></h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
          <div class="accordion-body">It's very simple! Register here. In your personal account, create wallet where you can store your FOX tokens. Then just send any amount to the displayed address in the your office.</div>
        </div>
        </div>

        <div class="accordion-item" data-aos="fade-up" data-aos-duration="1300">
        <h2 class="accordion-header" id="headingFour"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">What Is Cryptocurrency?</button></h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
          <div class="accordion-body">It's very simple! Register here. In your personal account, create wallet where you can store your FOX tokens. Then just send any amount to the displayed address in the your office.</div>
        </div>
        </div>

        <div class="accordion-item" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="accordion-header" id="headingFive"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">how does cryptocurrency work?</button></h2>
        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
          <div class="accordion-body">It's very simple! Register here. In your personal account, create wallet where you can store your FOX tokens. Then just send any amount to the displayed address in the your office.</div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== FAQ AREA END ======= -->

  <!-- ===== CTA AREA START ======= -->
  <div class="cta6">
    <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-5">
      <div class="heading6-w">
        <span class="span">Integration brands</span>
        <h2>Access A Library Of <br /> Pre-Built Templates</h2>
        <div class="space16"></div>
        <p>
        Seamlessly integrate your eSoft Builder with the 3K App <br /> to elevate your workflow and enhance of productivity.
        </p>
        <div class="space30"></div>
        <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn8"><span class="tb8">Get Started, It’s Free <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></a>
      </div>
      </div>
      <div class="col-lg-1"></div>

      <div class="col-lg-6">
      <div class="cta6-images">
        <div class="image1">
        <img src="/img/others/cta6-img.png" alt="" />
        </div>
        <div class="image2">
        <img src="/img/shapes/cta6-shape.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== CTA AREA END ======= -->

  <!-- ===== FOOTER AREA START ======= -->
  <div class="footer6 _relative">
    <div class="container bg">
    <div class="row">
      <div class="col-lg-5">
      <div class="subscribe-area">
        <h5>Subscribe to our newsletter</h5>
        <p>eSoft has exceeded our expectations every way. The ease with & which Search Engine</p>
        <div class="input-area">
        <input type="email" placeholder="Email Address" />
        <div class="button">
          <button class="theme-btn9"><span class="tb8">Get Started Now <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span></span></button>
        </div>
        </div>
      </div>
      </div>

      <div class="col-lg col-md-6 col-6">
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

      <div class="col-lg col-md-6 col-6">
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
        <a href="{{ route('any', 'index') }}"><img src="/img/logo/footer-logo6.png" alt="" /></a>
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
  </div>
  <!-- ===== FOOTER AREA END ======= -->
@endsection