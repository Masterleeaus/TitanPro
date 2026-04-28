@extends('templates.esoft.layouts.base' , [ 'title' => 'Time Tracker' , 'logo5' => true])

@section('body_attribute')
  class="body body5"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  @include('templates.esoft.layouts.partials.header.navbar2')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area5">
    <div class="container">
      <div class="hero5-bg">
        <div class="row">
          <div class="col-lg-7 m-auto text-center">
            <div class="main-heading">
              <span class="span" data-aos="zoom-in-left" data-aos-duration="700"><img src="/img/icons/main-span5.png" alt="" /> No 01 Time Tracker Software on Producthunt</span>
              <h1 class="text-anime-style-3">Stop Wasting Time, Start Tracking It.</h1>
              <div class="space16"></div>
              <p data-aos="fade-up" data-aos-duration="700">
                Boost your productivity, improve profitability, and reduce stress <br /> with our all-in-one time management solution.
              </p>
              <div class="space30"></div>
              <div class="buttons" data-aos="fade-up" data-aos-duration="900">
                <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
                <a href="{{ route('second', ['pages', 'features']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get a Demo</a>
              </div>
            </div>
          </div>
        </div>
        <div class="space80"></div>
        <div class="row">
          <div class="col-lg-12">
            <div class="main-image">
              <img src="/img/hero/hero5-main-image.png" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <div class="space60"></div>

  <!-- ===== SLIDER AREA START ======= -->
  <div class="hero5-slider">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="hero5-slider-hading">
            <p>Trusted by 50,000+ businesses to scale outbound sales and drive new revenue</p>
            <div class="hero5-slider-all">
              <div class="single-slider">
                <img src="/img/logo/hero5-slider1.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider2.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider3.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider4.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider5.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider1.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider2.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider3.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider4.png" alt="" />
              </div>
              <div class="single-slider">
                <img src="/img/logo/hero5-slider5.png" alt="" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== SLIDER AREA END ======= -->

  <!-- ===== CHOOSE AREA START ======= -->
  <div class="choose5 sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 m-auto text-center">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> Why Choose eSoft?</span>
            </p>
            <h2 class="text-anime-style-3">eSoft Helps You Gain Clarity And Control Over Your Workday.</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row">
        <div class="col-lg-4">
          <div class="choose-box" data-aos="zoom-in-up" data-aos-duration="700">
            <div class="image">
              <img src="/img/choose/choose5-img1.png" alt="" />
            </div>
            <div class="space20"></div>
            <div class="heading5">
              <h5>Seamless Project Tracking</h5>
              <div class="space16"></div>
              <p>Assign tasks to projects, track overall project time, and identify areas for improvement.</p>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="choose-box" data-aos="zoom-in-up" data-aos-duration="900">
            <div class="image">
              <img src="/img/choose/choose5-img2.png" alt="" />
            </div>
            <div class="space20"></div>
            <div class="heading5">
              <h5>Detailed Reporting</h5>
              <div class="space16"></div>
              <p>Generate comprehensive reports with insightful data visualizations. See exactly where your time is being spent and make informed decisions.</p>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="choose-box" data-aos="zoom-in-up" data-aos-duration="1100">
            <div class="image">
              <img src="/img/choose/choose5-img3.png" alt="" />
            </div>
            <div class="space20"></div>
            <div class="heading5">
              <h5>Flexible Time Tracking</h5>
              <div class="space16"></div>
              <p>Track time manually, automatically based on active applications, or use our mobile app for on-the-go tracking.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== CHOOSE AREA END ======= -->

  <!-- ===== TIME TRACK STEP START ======= -->
  <div class="time-track-steps">
    <div class="container">
      <div class="step-area">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="heading5">
              <h3 class="text-anime-style-3">Track Time Manually, Automatically Based On Active Applications</h3>
              <div class="space16"></div>
              <p data-aos="fade-right" data-aos-duration="700">Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
              <div class="space5"></div>
              <ul class="track-list" data-aos="fade-right" data-aos-duration="900">
                <li>
                  <span class="check"><i class="fa-solid fa-check"></i></span> Offline Functionality
                </li>
                <li>
                  <span class="check"><i class="fa-solid fa-check"></i></span> Multi-Platform Support
                </li>
                <li>
                  <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Dashboards
                </li>
              </ul>
              <div class="space30"></div>
              <div class="" data-aos="fade-right" data-aos-duration="900">
                <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="main-image" data-aos="zoom-out" data-aos-duration="800">
              <img src="/img/others/time-track-sec-main.png" alt="" />
            </div>
          </div>
        </div>
      </div>
      <div class="space60"></div>

      <div class="step-area step2">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="main-image" data-aos="zoom-out" data-aos-duration="800">
              <img src="/img/others/time-track-sec-main.png" alt="" />
            </div>
          </div>

          <div class="col-lg-5">
            <div class="heading5">
              <h3 class="text-anime-style-3">Simply Start A Timer For Each Task, Or Use Our Automatic Project Tracking Features.</h3>
              <div class="space16"></div>
              <p data-aos="fade-left" data-aos-duration="700">Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
              <div class="space5"></div>
              <ul class="track-list" data-aos="fade-left" data-aos-duration="700">
                <li>
                  <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Settings
                </li>
                <li>
                  <span class="check"><i class="fa-solid fa-check"></i></span> Sync Across Devices
                </li>
                <li>
                  <span class="check"><i class="fa-solid fa-check"></i></span> Reminders and Notifications
                </li>
              </ul>
              <div class="space30"></div>
              <div class="" data-aos="fade-left" data-aos-duration="700">
                <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="space100"></div>
  <!-- ===== TIME TRACK STEP END ======= -->

  <!-- ===== SERVICE AREA START ======= -->
  <div class="service5 sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> Choose by Industry</span>
            </p>
            <h2 class="text-anime-style-3">Industry-Specific Applications Of Our Time Tracker App</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>

      <div class="row">
        <div class="col-lg-12">
          <div class="service-tabs-area">
            <div class="tabs-boxs">
              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="800">
                  <button class="nav-link active" id="service-items1-tab" data-bs-toggle="pill" data-bs-target="#service-items1" type="button" role="tab" aria-controls="service-items1" aria-selected="true"><img src="/img/icons/service5-icon1.svg" alt="" /> <br /> <span>SEO</span></button>
                </li>
                <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="1000">
                  <button class="nav-link" id="service-items2-tab" data-bs-toggle="pill" data-bs-target="#service-items2" type="button" role="tab" aria-controls="service-items2" aria-selected="false"><img src="/img/icons/service5-icon2.svg" alt="" /> <br /> <span>Content Marketing</span></button>
                </li>
                <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="900">
                  <button class="nav-link" id="service-items3-tab" data-bs-toggle="pill" data-bs-target="#service-items3" type="button" role="tab" aria-controls="service-items3" aria-selected="false"><img src="/img/icons/service5-icon3.svg" alt="" /> <br /> <span>Market Research</span></button>
                </li>
                <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="1100">
                  <button class="nav-link" id="service-items4-tab" data-bs-toggle="pill" data-bs-target="#service-items4" type="button" role="tab" aria-controls="service-items4" aria-selected="false"><img src="/img/icons/service5-icon4.svg" alt="" /> <br /> <span>Advertising</span></button>
                </li>
                <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="900">
                  <button class="nav-link" id="service-items5-tab" data-bs-toggle="pill" data-bs-target="#service-items5" type="button" role="tab" aria-controls="service-items5" aria-selected="false"><img src="/img/icons/service5-icon5.svg" alt="" /> <br /> <span>Social Media</span></button>
                </li>
              </ul>
            </div>

            <div class="space60"></div>

            <div class="tabs-item-area" data-aos="fade-up" data-aos-duration="900">
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="service-items1" role="tabpanel" aria-labelledby="service-items1-tab">
                  <div class="service-3-bottom-area">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="tab-bottom-headding">
                          <div class="heading5">
                            <h3>Simply start a timer for each task, or use our automatic project tracking features.</h3>
                            <div class="space16"></div>
                            <p>Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
                            <div class="space5"></div>
                            <ul class="track-list">
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Settings
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Sync Across Devices
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Reminders and Notifications
                              </li>
                            </ul>
                            <div class="space30"></div>
                            <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="tab-bottom-images">
                          <div class="img1">
                            <img src="/img/others/tab5-img1.png" alt="" />
                          </div>
                          <div class="img2 shape-animaiton3">
                            <img src="/img/others/tab5-img2.png" alt="" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="service-items2" role="tabpanel" aria-labelledby="service-items2-tab">
                  <div class="service-3-bottom-area">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="tab-bottom-headding">
                          <div class="heading5">
                            <h3>Simply start a timer for each task, or use our automatic project tracking features.</h3>
                            <div class="space16"></div>
                            <p>Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
                            <div class="space5"></div>
                            <ul class="track-list">
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Settings
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Sync Across Devices
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Reminders and Notifications
                              </li>
                            </ul>
                            <div class="space30"></div>
                            <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="tab-bottom-images">
                          <div class="img1">
                            <img src="/img/others/tab5-img1.png" alt="" />
                          </div>
                          <div class="img2 shape-animaiton3">
                            <img src="/img/others/tab5-img2.png" alt="" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="service-items3" role="tabpanel" aria-labelledby="service-items3-tab">
                  <div class="service-3-bottom-area">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="tab-bottom-headding">
                          <div class="heading5">
                            <h3>Simply start a timer for each task, or use our automatic project tracking features.</h3>
                            <div class="space16"></div>
                            <p>Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
                            <div class="space5"></div>
                            <ul class="track-list">
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Settings
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Sync Across Devices
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Reminders and Notifications
                              </li>
                            </ul>
                            <div class="space30"></div>
                            <a href="#" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="tab-bottom-images">
                          <div class="img1">
                            <img src="/img/others/tab5-img1.png" alt="" />
                          </div>
                          <div class="img2 shape-animaiton3">
                            <img src="/img/others/tab5-img2.png" alt="" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="service-items4" role="tabpanel" aria-labelledby="service-items4-tab">
                  <div class="service-3-bottom-area">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="tab-bottom-headding">
                          <div class="heading5">
                            <h3>Simply start a timer for each task, or use our automatic project tracking features.</h3>
                            <div class="space16"></div>
                            <p>Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
                            <div class="space5"></div>
                            <ul class="track-list">
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Settings
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Sync Across Devices
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Reminders and Notifications
                              </li>
                            </ul>
                            <div class="space30"></div>
                            <a href="#" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="tab-bottom-images">
                          <div class="img1">
                            <img src="/img/others/tab5-img1.png" alt="" />
                          </div>
                          <div class="img2 shape-animaiton3">
                            <img src="/img/others/tab5-img2.png" alt="" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="service-items5" role="tabpanel" aria-labelledby="service-items5-tab">
                  <div class="service-3-bottom-area">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="tab-bottom-headding">
                          <div class="heading5">
                            <h3>Simply start a timer for each task, or use our automatic project tracking features.</h3>
                            <div class="space16"></div>
                            <p>Our intuitive interface makes tracking time a breeze. Simply start a timer for each task, or use our automatic project tracking features. No more clunky spreadsheets or wasted minutes logging hours.</p>
                            <div class="space5"></div>
                            <ul class="track-list">
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Settings
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Sync Across Devices
                              </li>
                              <li>
                                <span class="check"><i class="fa-solid fa-check"></i></span> Reminders and Notifications
                              </li>
                            </ul>
                            <div class="space30"></div>
                            <a href="#" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="tab-bottom-images">
                          <div class="img1">
                            <img src="/img/others/tab5-img1.png" alt="" />
                          </div>
                          <div class="img2 shape-animaiton3">
                            <img src="/img/others/tab5-img2.png" alt="" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <img src="/img/shapes/faq5-shape1.png" alt="" class="shape1" />
    <img src="/img/shapes/faq5-shape2.png" alt="" class="shape2" />
  </div>
  <!-- ===== SERVICE AREA END ======= -->

  <!-- ===== APPS AREA START ======= -->
  <div class="apps5-area sp">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="apps-image" data-aos="flip-right" data-aos-duration="800">
            <img src="/img/others/apps-img5.png" alt="" />
          </div>
        </div>
        <div class="col-lg-6">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> Intregrations</span>
            </p>
            <h2 class="text-anime-style-3">Integrate Your Time Tracker With 3000+ Apps</h2>
            <div class="space16"></div>
            <p data-aos="fade-left" data-aos-duration="600">Integrate our Time Tracker App with your favorite tools and software to streamline your workflow and boost productivity. Connect with calendar apps, project management tools, and other essential applications to centralize your tasks and time tracking data. By integrating our app with your existing tools, you can save time, reduce manual entry errors, and ensure a seamless experience across all your work platforms.</p>
            <div class="space30"></div>
            <div class="" data-aos="fade-left" data-aos-duration="800">
              <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started, It’s Free</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== APPS AREA END ======= -->

  <!-- ===== PRICING AREA START ======= -->
  <div class="pricing5 pb100">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> Pricing Plan</span>
            </p>
            <h2 class="text-anime-style-3">Choose The Right Plan For You</h2>
          </div>
        </div>
      </div>
      <div class="space30"></div>

      <div class="row">
        <div class="col-lg-6">
          <div class="pricing-box" data-aos="fade-up" data-aos-duration="700">
            <div class="row">
              <div class="col-md-5">
                <div class="heading5">
                  <span class="gradint">Pro Plan</span>
                  <h3>$9.99/month</h3>
                  <p>
                    Great for occasional <br /> prospecting.
                  </p>
                  <div class="space30"></div>
                  <a href="#" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started</a>
                </div>
              </div>
              <div class="col-md-7">
                <ul class="plan-list">
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Time Tracking
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Task Management
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Collaboration
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Reports and Insights
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Categories
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Email Support
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="pricing-box" data-aos="fade-up" data-aos-duration="900">
            <div class="row">
              <div class="col-md-5">
                <div class="heading5">
                  <span class="gradint">Pro Plan</span>
                  <h3>$9.99/month</h3>
                  <p>
                    Great for occasional <br /> prospecting.
                  </p>
                  <div class="space30"></div>
                  <a href="#" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Get Started</a>
                </div>
              </div>
              <div class="col-md-7">
                <ul class="plan-list">
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Time Tracking
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Task Management
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Collaboration
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Reports and Insights
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Customizable Categories
                  </li>
                  <li>
                    <span class="check"><i class="fa-solid fa-check"></i></span> Email Support
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== PRICING AREA END ======= -->

  <!-- ===== TES AREA START ======= -->
  <div class="tes5 sp">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-center m-auto">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> Testimonials</span>
            </p>
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
                <img src="/img/icons/qute5.svg" alt="" />
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
                <img src="/img/icons/qute5.svg" alt="" />
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
                <img src="/img/icons/qute5.svg" alt="" />
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
                <img src="/img/icons/qute5.svg" alt="" />
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

    <img src="/img/shapes/faq5-shape1.png" alt="" class="shape1" />
    <img src="/img/shapes/faq5-shape2.png" alt="" class="shape2" />
  </div>
  <!-- ===== TES AREA END ======= -->

  <!-- ===== BLOG AREA START ======= -->
  <div class="blog2 blog5 sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> Our Blog</span>
            </p>
            <h2 class="text-anime-style-3">Our Latest Blog & News</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row">
        <div class="col-lg-6">
          <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="800">
            <div class="image">
              <img src="/img/blog/blog5-img1.png" alt="" />
            </div>
            <div class="headding">
              <div class="tags">
                <a href="#"><img src="/img/icons/blog2-icon1.png" alt="" /> 10 October 2023</a>
                <a href="#"><img src="/img/icons/blog2-icon2.png" alt="" /> Rabby Mahmud</a>
              </div>
              <h4><a href="{{ route('second', ['blogs', 'details']) }}">The Ultimate Email Campaign Playbook</a></h4>
              <p>Effortlessly design stunning emails, automate your market workflow, & precisely target your audience for maximum.</p>
              <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read more <span><i class="fa-regular fa-arrow-right"></i></span></a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="1000">
            <div class="image">
              <img src="/img/blog/blog5-img2.png" alt="" />
            </div>
            <div class="headding">
              <div class="tags">
                <a href="#"><img src="/img/icons/blog2-icon1.png" alt="" /> 10 October 2023</a>
                <a href="#"><img src="/img/icons/blog2-icon2.png" alt="" /> Rabby Mahmud</a>
              </div>
              <h4><a href="{{ route('second', ['blogs', 'details']) }}">Email Design: A Deep Dive in Visual Impact</a></h4>
              <p>Effortlessly design stunning emails, automate your market workflow, & precisely target your audience for maximum.</p>
              <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read more <span><i class="fa-regular fa-arrow-right"></i></span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== BLOG AREA END ======= -->

  <!-- ===== FAQ AREA START ======= -->
  <div class="faq5 sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="heading5">
            <p class="title" data-aos="zoom-in-left" data-aos-duration="700">
              <span class="span"><img src="/img/icons/heading5-span.png" alt="" /> FAQ</span>
            </p>
            <h2 class="text-anime-style-3">Frequently Asked Questions</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row">
        <div class="col-lg-6">
          <div class="accordion accordion1" data-aos="fade-up" data-aos-duration="1000" id="accordionExample">
            <div class="accordion-item active">
              <h2 class="accordion-header" id="headingOne"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">What devices are compatible with the Time Tracker App?</button></h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Can I upgrade or downgrade my plan at any time?</button></h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">How does the collaboration feature work in the App?</button></h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFour"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Can I export my time tracking data from the app?</button></h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="accordion accordion1" data-aos="fade-up" data-aos-duration="800" id="accordionExample2">
            <div class="accordion-item active">
              <h2 class="accordion-header" id="headingOne2"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2">Is my data secure with the Time Tracker App?</button></h2>
              <div id="collapseOne2" class="accordion-collapse collapse show" aria-labelledby="headingOne2" data-bs-parent="#accordionExample2">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo2"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">Are there any discounts for long-term subscriptions?</button></h2>
              <div id="collapseTwo2" class="accordion-collapse collapse" aria-labelledby="headingTwo2" data-bs-parent="#accordionExample2">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree2"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree2" aria-expanded="false" aria-controls="collapseThree2">Does the Time Tracker App offer customer support?</button></h2>
              <div id="collapseThree2" class="accordion-collapse collapse" aria-labelledby="headingThree2" data-bs-parent="#accordionExample2">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFour2"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour2" aria-expanded="false" aria-controls="collapseFour2">Can I export my time tracking data from the app?</button></h2>
              <div id="collapseFour2" class="accordion-collapse collapse" aria-labelledby="headingFour2" data-bs-parent="#accordionExample2">
                <div class="accordion-body">Our Time Tracker App is compatible with desktop computers, laptops, tablets, and smartphones running on Windows, macOS, iOS, and Android operating systems.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <img src="/img/shapes/faq5-shape1.png" alt="" class="shape1" />
    <img src="/img/shapes/faq5-shape2.png" alt="" class="shape2" />
  </div>
  <!-- ===== FAQ AREA END ======= -->

  <div class="space100"></div>

  <!-- ===== CTA AREA START ======= -->
  <div class="cta5">
    <div class="container">
      <div class="cta5-bg">
        <div class="row">
          <div class="col-lg-6">
            <div class="heading5-w">
              <h2>Take Control Of Your Time Today!</h2>
              <div class="space16"></div>
              <p>Don't let precious moments slip away. Sign up now to start optimizing your productivity with our Time Tracker App.</p>
              <div class="space30"></div>
              <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn7"><span class="arrow"><img src="/img/icons/home5-btn-icon1.svg" alt="" /></span> Sign Up Now</a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="main-image">
              <img src="/img/others/cta5-main-img.png" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== CTA AREA END ======= -->

  <!-- ===== FOOTER AREA START ======= -->
  <div class="footer5 _relative">
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
        <div class="col-lg col-md-6 col-6">
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

        <div class="col-lg col-md-6 col-6">
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

        <div class="col-lg col-md-6 col-5">
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

        <div class="col-lg-3 col-md-6 col-7">
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
              <a href="{{ route('any', 'index') }}"><img src="/img/logo/footer-logo5.png" alt="" /></a>
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
