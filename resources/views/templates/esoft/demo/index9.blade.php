@extends('templates.esoft.layouts.base' , [ 'title' => 'Social Media Management & Schedule'])

@section('body_attribute')
  class="body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  @include('templates.esoft.layouts.partials.header.navbar9')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area4" style="background-image: url(/img/bg/header4-bg.png); background-repeat: no-repeat; background-size: cover; background-position: bottom center;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7 m-auto text-center">
          <div class="main-headding pbmit-heading-subheading animation-style2">
            <span class="span"><img src="/img/icons/span4-icon.png" alt="" /> Create Social Media Content. Faster.</span>
            <h1 class="text-anime-style-3">Social Media Management And Scheduling Refined</h1>
            <p data-aos="fade-up" data-aos-duration="800">Welcome to the digital era's command centre for your online presence! At eSoft, redefine the landscape of social media management and scheduling, offering you a seamless.</p>

            <div class="butons" data-aos="fade-up" data-aos-duration="1000">
              <a href="#" class="theme-btn4">Start 14-Day Free Trail</a>
              <div class="video-btn">
                <a href="https://www.youtube.com/watch?v=Hz2F_S3Tl0Y" class="play-btn"><span><i class="fa-solid fa-play"></i></span> See How It Works</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="hero4-images-all">
            <div class="image1">
              <img src="/img/hero/hero4-img1.png" alt="" />
            </div>
            <div class="image2">
              <img src="/img/hero/hero4-img2.png" alt="" />
            </div>
            <div class="image3 shape-animaiton3">
              <img src="/img/hero/hero4-img3.png" alt="" />
            </div>
            <div class="image4 shape-animaiton3">
              <img src="/img/hero/hero4-img4.png" alt="" />
            </div>
            <div class="image5 shape-animaiton2">
              <img src="/img/hero/hero4-img5.png" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <!-- ===== SERVICE AREA START ======= -->
  <div class="service4 sp _relative">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 m-auto text-center">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">Powerful Tool</span>
            <h2 class="text-anime-style-3">Powerful Social Media Management Tool</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="" data-aos="zoom-in-up" data-aos-duration="800">
            <div class="service-box">
              <div class="icon">
                <img src="/img/icons/service4-icon1.png" alt="" />
              </div>
              <div class="headding4">
                <h4><a href="{{ route('second', ['pages', 'features']) }}">Social Media Scheduling</a></h4>
                <div class="space16"></div>
                <p>Efficiently managing your social media & presence is the cornerstone of a successful online strategy, & social media scheduling.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="" data-aos="zoom-in-up" data-aos-duration="1000">
            <div class="service-box">
              <div class="icon">
                <img src="/img/icons/service4-icon2.png" alt="" />
              </div>
              <div class="headding4">
                <h4><a href="{{ route('second', ['pages', 'features']) }}">Social Media Automation</a></h4>
                <div class="space16"></div>
                <p>Improve your social media game with our cutting-edge scheduling solutions at eSoft. Our platform goes beyond the basics.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="" data-aos="zoom-in-up" data-aos-duration="1100">
            <div class="service-box">
              <div class="icon">
                <img src="/img/icons/service4-icon4.png" alt="" />
              </div>
              <div class="headding4">
                <h4><a href="{{ route('second', ['pages', 'features']) }}">Social Media Management</a></h4>
                <div class="space16"></div>
                <p>Our social media scheduling tools not only simplify the process but also empower you to strategically plan & execute manage.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <img class="shape1" src="/img/shapes/home4-shape1.png" alt="" />
  </div>
  <!-- ===== SERVICE AREA END ======= -->

  <!-- ===== WORK AREA START ======= -->
  <div class="work5 sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 m-auto text-center">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">How It Works</span>
            <h2 class="text-anime-style-3">Social Media Marketing With eSoft</h2>
            <div class="space16"></div>
            <p data-aos="fade-up" data-aos-duration="800">We understand the importance of a well-timed post, and our scheduling features enable you to reach your audience at optimal moments, maximising engagement and impact with an intuitive.</p>
          </div>
        </div>
      </div>

      <div class="space60"></div>

      <div class="row">
        <div class="col-lg-12">
          <div class="work5-tabs-all" data-aos="fade-up" data-aos-duration="1000">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="work-items1-tab" data-bs-toggle="pill" data-bs-target="#work-items1" type="button" role="tab" aria-controls="work-items1" aria-selected="true"><span><img src="/img/icons/work5-icon1.png" alt="" /></span> Collection</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="work-items2-tab" data-bs-toggle="pill" data-bs-target="#work-items2" type="button" role="tab" aria-controls="work-items2" aria-selected="false"><span><img src="/img/icons/work5-icon2.png" alt="" /></span> Scheduling</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="work-items3-tab" data-bs-toggle="pill" data-bs-target="#work-items3" type="button" role="tab" aria-controls="work-items3" aria-selected="false"><span><img src="/img/icons/work5-icon3.png" alt="" /></span> Automation</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="work-items4-tab" data-bs-toggle="pill" data-bs-target="#work-items4" type="button" role="tab" aria-controls="work-items4" aria-selected="false"><span><img src="/img/icons/work5-icon4.png" alt="" /></span> Reviews</button>
              </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="work-items1" role="tabpanel" aria-labelledby="work-items1-tab">
                <div class="work-tab-body">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      <div class="headding4">
                        <h3>Management & Scheduling Solutions</h3>
                        <div class="space16"></div>
                        <p>Social media with ease. Our suite goes beyond basic posting, providing a you with an intelligent solutions to craft success in the competitive online.</p>

                        <div class="media-all">
                          <h6>Social Media Scheduling Benefits:</h6>
                          <div class="row">
                            <div class="col-lg">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Global Research
                                </p>
                              </div>
                            </div>
                            <div class="col-lg">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Brand Image
                                </p>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Strategic Planning
                                </p>
                              </div>
                            </div>
                            <div class="col-lg">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Content Variety
                                </p>
                              </div>
                            </div>
                            <div class="col-lg">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Reduced Stress
                                </p>
                              </div>
                            </div>
                          </div>
                          <div class="space30"></div>
                          <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn5">Start 14-Day Free Trail</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="work4-images">
                        <div class="image1">
                          <img src="/img/work/work4-img1.png" alt="" />
                        </div>
                        <div class="image2">
                          <img src="/img/work/work4-img2.png" alt="" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="work-items2" role="tabpanel" aria-labelledby="work-items2-tab">
                <div class="work-tab-body">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      <div class="headding4">
                        <h3>Management & Scheduling Solutions</h3>
                        <div class="space16"></div>
                        <p>Social media with ease. Our suite goes beyond basic posting, providing a you with an intelligent solutions to craft success in the competitive online.</p>

                        <div class="media-all">
                          <h6>Social Media Scheduling Benefits:</h6>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                          </div>
                          <div class="space30"></div>
                          <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn5">Start 14-Day Free Trail</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="work4-images">
                        <div class="image1">
                          <img src="/img/work/work4-img1.png" alt="" />
                        </div>
                        <div class="image2">
                          <img src="/img/work/work4-img2.png" alt="" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="work-items3" role="tabpanel" aria-labelledby="work-items3-tab">
                <div class="work-tab-body">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      <div class="headding4">
                        <h3>Management & Scheduling Solutions</h3>
                        <div class="space16"></div>
                        <p>Social media with ease. Our suite goes beyond basic posting, providing a you with an intelligent solutions to craft success in the competitive online.</p>

                        <div class="media-all">
                          <h6>Social Media Scheduling Benefits:</h6>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                          </div>
                          <div class="space30"></div>
                          <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn5">Start 14-Day Free Trail</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="work4-images">
                        <div class="image1">
                          <img src="/img/work/work4-img1.png" alt="" />
                        </div>
                        <div class="image2">
                          <img src="/img/work/work4-img2.png" alt="" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="work-items4" role="tabpanel" aria-labelledby="work-items4-tab">
                <div class="work-tab-body">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      <div class="headding4">
                        <h3>Management & Scheduling Solutions</h3>
                        <div class="space16"></div>
                        <p>Social media with ease. Our suite goes beyond basic posting, providing a you with an intelligent solutions to craft success in the competitive online.</p>

                        <div class="media-all">
                          <h6>Social Media Scheduling Benefits:</h6>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="list-headding">
                                <p>
                                  <span><i class="fa-solid fa-check"></i></span> Time Efficiency
                                </p>
                              </div>
                            </div>
                          </div>
                          <div class="space30"></div>
                          <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn5">Start 14-Day Free Trail</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="work4-images">
                        <div class="image1">
                          <img src="/img/work/work4-img1.png" alt="" />
                        </div>
                        <div class="image2">
                          <img src="/img/work/work4-img2.png" alt="" />
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
  <!-- ===== WORK AREA END ======= -->

  <!-- ===== POST AREA START ======= -->
  <div class="post-area sp _relative">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 m-auto text-center">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">Schedule Post</span>
            <h2 class="text-anime-style-3">Any Schedule Post?</h2>
            <div class="space16"></div>
            <p data-aos="fade-up" data-aos-duration="900">We understand the importance of well-timed post, and our scheduling features enable you to reach your audience.</p>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row align-items-center">
        <div class="col-lg-5">
          <div class="post-left-area">
            <div class="image1" data-aos="zoom-out" data-aos-duration="800">
              <img src="/img/blog/post-image1.png" alt="" />
            </div>
            <div class="image2" data-aos="flip-right" data-aos-duration="1000">
              <img src="/img/blog/post-image2.png" alt="" />
            </div>
            <div class="image3">
              <img src="/img/shapes/post-shape.png" alt="" />
            </div>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="big-img">
            <img src="/img/blog/post-rihgt-area-img.png" alt="" />
          </div>
        </div>
      </div>
    </div>

    <img class="shape1" src="/img/shapes/home4-shape2.png" alt="" />
  </div>
  <!-- ===== POST AREA END ======= -->

  <!-- ===== MIDEA AREA START ======= -->
  <div class="midea-markating sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 m-auto text-center">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">Social Media Marketing</span>
            <h2 class="text-anime-style-3">What Do You Get From Social Media Marketing</h2>
          </div>
        </div>
      </div>
      <div class="space60"></div>
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="image" data-aos="flip-right" data-aos-duration="900">
            <img src="/img/others/media-marketing-img.png" alt="" />
          </div>
        </div>

        <div class="col-lg-6">
          <div class="media-box-all">
            <div class="" data-aos-duration="900" data-aos="fade-left">
              <div class="single-box">
                <div class="icon">
                  <img src="/img/icons/media-icon1.png" alt="" />
                </div>
                <div class="headding4">
                  <h4><a href="#">Automate Your Work</a></h4>
                  <div class="space10"></div>
                  <p>Increase audience engagement by posting during peak hours when your followers are most active.</p>
                </div>
              </div>
            </div>

            <div class="" data-aos-duration="1000" data-aos="fade-left">
              <div class="single-box">
                <div class="icon">
                  <img src="/img/icons/media-icon2.png" alt="" />
                </div>
                <div class="headding4">
                  <h4><a href="#">Automate Your Work</a></h4>
                  <div class="space10"></div>
                  <p>Build a professional and organised brand image by having a well-planned and cohesive social</p>
                </div>
              </div>
            </div>

            <div class="" data-aos-duration="1100" data-aos="fade-left">
              <div class="single-box">
                <div class="icon">
                  <img src="/img/icons/media-icon3.png" alt="" />
                </div>
                <div class="headding4">
                  <h4><a href="#">Create Content Easily</a></h4>
                  <div class="space10"></div>
                  <p>Reach a global audience by scheduling posts to go live at times that are optimal for different time save.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== MIDEA AREA END ======= -->

  <!-- ===== INTEGRATION AREA start ======= -->
  <div class="integration sp _relative">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">Our Integration</span>
            <h2 class="text-anime-style-3">Connect Entire Workflows</h2>
            <div class="space16"></div>
            <p data-aos="fade-right" data-aos-duration="900">Our scheduling tools empower you to efficiently plan and organise your social media content, freeing from the constraints of real-time posting. This not only saves valuable hours but also ensures a consistent and reliable stream of content for your audience. The strategic planning capabilities allow you to align your posts with key events, campaigns, and trends.</p>
            <div class="space30"></div>
            <div class="" data-aos="fade-right" data-aos-duration="1000">
              <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn5">Browse All Integration</a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="apps" data-aos="zoom-in-up" data-aos-duration="700">
            <img src="/img/others/home4-apps.png" alt="" />
          </div>
        </div>
      </div>
    </div>

    <img class="shape1" src="/img/shapes/home4-shape2.png" alt="" />
  </div>
  <!-- ===== INTEGRATION AREA END ======= -->

  <!-- ===== TESTIMONIAL AREA START ======= -->
  <div class="testimonial4 sp">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">Our Testimonials</span>
            <h2 class="text-anime-style-3">What Our Client Says</h2>
          </div>
        </div>
      </div>

      <div class="space60"></div>
      <div class="row">
        <div class="col-lg-10 m-auto _relative">
          <div class="tes4-big-slider" data-aos="fade-up" data-aos-duration="800">
            <div class="single-slider">
              <div class="row">
                <div class="col-lg-12">
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
                  <p class="pera">“As a small business owner, time is of the essence. eSoft has been a lifesaver for me. The scheduling tools are intuitive, and I can easily plan my posts for the week in just a few clicks. The consistency it brings to my social media presence has significantly boosted my brand image. Plus, the analytics help me understand what works and what doesn't. A must-have for anyone juggling multiple responsibilities!”</p>

                  <img src="/img/icons/tes4-icon.png" alt="" class="icon" />
                  <div class="bottom-headding">
                    <div class="image">
                      <img src="/img/testimonial/tes4-img.png" alt="" />
                    </div>
                    <div class="headding">
                      <h4><a href="#">Pat Cummins</a></h4>
                      <p>Ceo Biosynthesis</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="single-slider">
              <div class="row">
                <div class="col-lg-12">
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
                  <p class="pera">“As a small business owner, time is of the essence. eSoft has been a lifesaver for me. The scheduling tools are intuitive, and I can easily plan my posts for the week in just a few clicks. The consistency it brings to my social media presence has significantly boosted my brand image. Plus, the analytics help me understand what works and what doesn't. A must-have for anyone juggling multiple responsibilities!”</p>

                  <img src="/img/icons/tes4-icon.png" alt="" class="icon" />
                  <div class="bottom-headding">
                    <div class="image">
                      <img src="/img/testimonial/tes4-img.png" alt="" />
                    </div>
                    <div class="headding">
                      <h4><a href="#">Pat Cummins</a></h4>
                      <p>Ceo Biosynthesis</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="testimonial-arrows">
            <button class="testimonial-prev-arrow"><i class="fa-regular fa-arrow-left"></i></button>
            <button class="testimonial-next-arrow"><i class="fa-regular fa-arrow-right"></i></button>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8 m-auto text-center">
          <div class="p-headding">
            <p>Join 4,000+ companies already growing</p>
          </div>
          <div class="tes4-logo-slider1">
            <div class="single-logo">
              <img src="/img/logo/teas4-logo1.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo2.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo3.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo4.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo5.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo1.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo2.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo3.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo4.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/teas4-logo5.png" alt="" />
            </div>
          </div>

          <div class="tes4-logo-slider2">
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo1.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo2.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo3.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo4.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo5.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo1.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo2.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo3.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo4.png" alt="" />
            </div>
            <div class="single-logo">
              <img src="/img/logo/tes2-slider2-logo5.png" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== TESTIMONIAL AREA END ======= -->

  <!-- ===== BLOG AREA START ======= -->
  <div class="blog4 sp _relative">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="headding4 pbmit-heading-subheading animation-style2">
            <span class="span">Our Blog</span>
            <h2 class="text-anime-style-3">Our Latest Articles & Blog</h2>
          </div>
        </div>
      </div>

      <div class="space30"></div>
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="blog4-single-box" data-aos="zoom-in-up" data-aos-duration="800">
            <div class="image">
              <img src="/img/blog/blog4-img1.png" alt="" />
            </div>
            <div class="headding4">
              <a href="#" class="date"><img src="/img/icons/date-icon4.png" alt="" /> 10 October 2023</a>
              <h5><a href="{{ route('second', ['blogs', 'details']) }}">The Ultimate Guide Social Media Manage & Scheduling Success</a></h5>
              <p>Boost your online presence by maintaining an active & engaging social media profile.</p>
              <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read More <span><i class="fa-regular fa-arrow-right"></i></span></a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="blog4-single-box" data-aos="zoom-in-up" data-aos-duration="1000">
            <div class="image">
              <img src="/img/blog/blog4-img2.png" alt="" />
            </div>
            <div class="headding4">
              <a href="#" class="date"><img src="/img/icons/date-icon4.png" alt="" /> 24 April 2023</a>
              <h5><a href="{{ route('second', ['blogs', 'details']) }}">The ROI of Streamlining with Social Media World Management</a></h5>
              <p>Minimise the stress associated with last- and minute posting, allowing you to focus .</p>
              <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read More <span><i class="fa-regular fa-arrow-right"></i></span></a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="blog4-single-box" data-aos="zoom-in-up" data-aos-duration="1200">
            <div class="image">
              <img src="/img/blog/blog4-img1.png" alt="" />
            </div>
            <div class="headding4">
              <a href="#" class="date"><img src="/img/icons/date-icon4.png" alt="" /> 10 August 2023</a>
              <h5><a href="{{ route('second', ['blogs', 'details']) }}">The Hidden Best Benefits of Social Media Scheduling for Businesses</a></h5>
              <p>Collaborate more efficiently with your team, ensuring everyone is on the same.</p>
              <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read More <span><i class="fa-regular fa-arrow-right"></i></span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <img class="shape1" src="/img/shapes/home4-shape1.png" alt="" />
  </div>
  <!-- ===== BLOG AREA END ======= -->

  <!-- ===== CTA AREA START ======= -->
  <div class="cta4" style="background-image: url(/img/bg/cta4-bg.png); background-position: bottom center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 m-auto text-center">
          <div class="headding4-w">
            <h2>Join The Revolution! Schedule Your Way To Social Media Excellence</h2>
            <p data-aos="fade-up" data-aos-duration="800">Welcome to the digital era's command centre for your online presence! At eSoft, redefine the landscape of social media management and scheduling, offering you a seamless.</p>

            <div class="buttons" data-aos="fade-up" data-aos-duration="1000">
              <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn4">Get Started Now</a>
              <a href="{{ route('second', ['auth', 'account']) }}" class="theme-btn6">Start 14-Day Free Trail</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== CTA AREA END ======= -->

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
  </div>
  <!-- ===== FOOTER AREA END ======= -->
@endsection
