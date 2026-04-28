@extends('templates.esoft.layouts.base' , [ 'title' => 'Email Marketing Sass Landing Page' , 'logo3' => true])

@section('body_attribute')
  class="body3"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader')

  @include('templates.esoft.layouts.partials.header-single.navbar8')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area3" style="background-image: url(/img/bg/header3-bg.png);">
    <div class="container">
      <div class="hero4-mid-area">
        <div class="row">
          <div class="col-lg-6 m-auto text-center">
            <div class="main-headding">
              <h1>Elevate Your Rankings with eSoft The Ultimate Best SEO Solution</h1>
              <p>eSoft combines cutting-edge technology with user-friendly features, an making it the perfect companion for businesses & individuals looking.</p>

              <form action="#">
                <input type="email" placeholder="Enter Your Website Url" />
                <div class="action-btn">
                  <button class="immiPressBtn"><span>Book a Consultation</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="main-img">
          <img src="/img/hero/header3-main-img.png" alt="" />
        </div>
      </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0">
    <!-- ===== SERVICE AREA START ======= -->
    <div class="service3 sp" id="service">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 m-auto text-center">
            <div class="headding3">
              <h2 data-aos="fade-up" data-aos-duration="800">Your SEO & Digital Marketing Solution</h2>
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
                    <button class="nav-link active" id="service-items1-tab" data-bs-toggle="pill" data-bs-target="#service-items1" type="button" role="tab" aria-controls="service-items1" aria-selected="true"><img src="/img/icons/service3-icon1.png" alt="" /> <br /> <span>SEO</span></button>
                  </li>
                  <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="1000">
                    <button class="nav-link" id="service-items2-tab" data-bs-toggle="pill" data-bs-target="#service-items2" type="button" role="tab" aria-controls="service-items2" aria-selected="false"><img src="/img/icons/service3-icon2.png" alt="" /> <br /> <span>Content Marketing</span></button>
                  </li>
                  <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="900">
                    <button class="nav-link" id="service-items3-tab" data-bs-toggle="pill" data-bs-target="#service-items3" type="button" role="tab" aria-controls="service-items3" aria-selected="false"><img src="/img/icons/service3-icon3.png" alt="" /> <br /> <span>Market Research</span></button>
                  </li>
                  <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="1100">
                    <button class="nav-link" id="service-items4-tab" data-bs-toggle="pill" data-bs-target="#service-items4" type="button" role="tab" aria-controls="service-items4" aria-selected="false"><img src="/img/icons/service3-icon4.png" alt="" /> <br /> <span>Advertising</span></button>
                  </li>
                  <li class="nav-item" role="presentation" data-aos="fade-up" data-aos-duration="900">
                    <button class="nav-link" id="service-items5-tab" data-bs-toggle="pill" data-bs-target="#service-items5" type="button" role="tab" aria-controls="service-items5" aria-selected="false"><img src="/img/icons/service3-icon5.png" alt="" /> <br /> <span>Social Media</span></button>
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
                            <h3>Elevate SEO Rankings with eSoft</h3>
                            <div class="space16"></div>
                            <p>Elevate your online presence effortlessly as our advanced tools an empower you to improve search engine rankings, driving a surge.</p>

                            <div class="space30"></div>
                            <ul class="list">
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Increased Organic Traffic
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Content Optimization Tools
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Regular Performance Monitoring
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Improved Search Engine Rankings
                              </li>
                            </ul>

                            <div class="space30"></div>

                            <a class="immiPressBtn" href="{{ route('second', ['pages', 'contact']) }}"><span>Boost My Rankings</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="tab-bottom-images">
                            <div class="img1">
                              <img src="/img/service/service3-img1.png" alt="" />
                            </div>
                            <div class="img2 shape-animaiton3">
                              <img src="/img/service/service3-img2.png" alt="" />
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
                            <h3>Elevate Content Marketing Rankings with eSoft</h3>
                            <div class="space16"></div>
                            <p>Elevate your online presence effortlessly as our advanced tools an empower you to improve search engine rankings, driving a surge.</p>

                            <div class="space30"></div>
                            <ul class="list">
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Increased Organic Traffic
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Content Optimization Tools
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Regular Performance Monitoring
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Improved Search Engine Rankings
                              </li>
                            </ul>

                            <div class="space30"></div>

                            <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Boost My Rankings</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="tab-bottom-images">
                            <div class="img1">
                              <img src="/img/service/service3-img1.png" alt="" />
                            </div>
                            <div class="img2 shape-animaiton3">
                              <img src="/img/service/service3-img2.png" alt="" />
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
                            <h3>Elevate Market Research Rankings with eSoft</h3>
                            <div class="space16"></div>
                            <p>Elevate your online presence effortlessly as our advanced tools an empower you to improve search engine rankings, driving a surge.</p>

                            <div class="space30"></div>
                            <ul class="list">
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Increased Organic Traffic
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Content Optimization Tools
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Regular Performance Monitoring
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Improved Search Engine Rankings
                              </li>
                            </ul>

                            <div class="space30"></div>

                            <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Boost My Rankings</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="tab-bottom-images">
                            <div class="img1">
                              <img src="/img/service/service3-img1.png" alt="" />
                            </div>
                            <div class="img2 shape-animaiton3">
                              <img src="/img/service/service3-img2.png" alt="" />
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
                            <h3>Elevate Advertising Rankings with eSoft</h3>
                            <div class="space16"></div>
                            <p>Elevate your online presence effortlessly as our advanced tools an empower you to improve search engine rankings, driving a surge.</p>

                            <div class="space30"></div>
                            <ul class="list">
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Increased Organic Traffic
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Content Optimization Tools
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Regular Performance Monitoring
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Improved Search Engine Rankings
                              </li>
                            </ul>

                            <div class="space30"></div>

                            <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Boost My Rankings</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="tab-bottom-images">
                            <div class="img1">
                              <img src="/img/service/service3-img1.png" alt="" />
                            </div>
                            <div class="img2 shape-animaiton3">
                              <img src="/img/service/service3-img2.png" alt="" />
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
                            <h3>Elevate Social Media Rankings with eSoft</h3>
                            <div class="space16"></div>
                            <p>Elevate your online presence effortlessly as our advanced tools an empower you to improve search engine rankings, driving a surge.</p>

                            <div class="space30"></div>
                            <ul class="list">
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Increased Organic Traffic
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Content Optimization Tools
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Regular Performance Monitoring
                              </li>
                              <li>
                                <span><i class="fa-solid fa-check"></i></span> Improved Search Engine Rankings
                              </li>
                            </ul>

                            <div class="space30"></div>

                            <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Boost My Rankings</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="tab-bottom-images">
                            <div class="img1">
                              <img src="/img/service/service3-img1.png" alt="" />
                            </div>
                            <div class="img2 shape-animaiton3">
                              <img src="/img/service/service3-img2.png" alt="" />
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
    </div>
    <!-- ===== SERVICE AREA END ======= -->

    <!-- ===== STIKY AREA START ======= -->
    <div class="stiky-sec sp" id="stiky" style="background-image: url(/img/bg/stiky-bg.png); background-position: center center; background-repeat: no-repeat; background-size: cover;">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 m-auto text-center">
            <div class="headding3">
              <h2>Boost Rankings: Introducing eSoft For SEO Excellence</h2>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="stiky-sec-boxs">
              <div class="stiky-single">
                <div class="row align-items-center">
                  <div class="col-lg-6">
                    <div class="headding">
                      <h3>Website Competitor Analysis:</h3>
                      <div class="space16"></div>
                      <p>Dive into the world of keywords with our powerful research an tools. Identify high-performing keywords relevant to your SEO niche, allowing you to tailor your content strategy and.</p>

                      <div class="space20"></div>
                      <ul class="list">
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Content Optimization Tools
                        </li>
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Regular Performance Monitoring
                        </li>
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Improved Search Engine Rankings
                        </li>
                      </ul>
                      <div class="space30"></div>

                      <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Unlock SEO Power</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="stiky-sec-img">
                      <img src="/img/others/stiky-sec-img1.png" alt="" />
                      <img class="shape" src="/img/shapes/stiky-sec-shape.png" alt="" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="stiky-single">
                <div class="row align-items-center">
                  <div class="col-lg-6">
                    <div class="headding">
                      <h3>Website Competitor Analysis:</h3>
                      <div class="space16"></div>
                      <p>Dive into the world of keywords with our powerful research an tools. Identify high-performing keywords relevant to your SEO niche, allowing you to tailor your content strategy and.</p>

                      <div class="space20"></div>
                      <ul class="list">
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Content Optimization Tools
                        </li>
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Regular Performance Monitoring
                        </li>
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Improved Search Engine Rankings
                        </li>
                      </ul>
                      <div class="space30"></div>

                      <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Unlock SEO Power</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="stiky-sec-img">
                      <img src="/img/others/stiky-sec-img1.png" alt="" />
                      <img class="shape" src="/img/shapes/stiky-sec-shape.png" alt="" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="stiky-single">
                <div class="row align-items-center">
                  <div class="col-lg-6">
                    <div class="headding">
                      <h3>Website Competitor Analysis:</h3>
                      <div class="space16"></div>
                      <p>Dive into the world of keywords with our powerful research an tools. Identify high-performing keywords relevant to your SEO niche, allowing you to tailor your content strategy and.</p>

                      <div class="space20"></div>
                      <ul class="list">
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Content Optimization Tools
                        </li>
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Regular Performance Monitoring
                        </li>
                        <li>
                          <span class="arrow"><i class="fa-regular fa-check"></i></span> Improved Search Engine Rankings
                        </li>
                      </ul>
                      <div class="space30"></div>

                      <a class="immiPressBtn" href="{{ route('second', ['pages', 'features']) }}"><span>Unlock SEO Power</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="stiky-sec-img">
                      <img src="/img/others/stiky-sec-img1.png" alt="" />
                      <img class="shape" src="/img/shapes/stiky-sec-shape.png" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ===== STIKY AREA END ======= -->

    <!-- ===== PRIICING AREA START ======= -->
    <div class="pricing-plan sp" id="pricing">
      <div class="container">
        <div class="row">
          <div class="col-md-6 m-auto text-center">
            <div class="headding3">
              <h2 data-aos="fade-up" data-aos-duration="800">Our Plan Makes You Feel <span class="after">More</span> Comfortable In Financial</h2>
              <p></p>
            </div>
          </div>
        </div>
        <div class="princing-plans">
          <div class="container">
            <div class="row">
              <div class="col-12 text-center">
                <div class="plan-toggle-wrap" data-aos="fade-up" data-aos-duration="1000">
                  <div class="toggle-inner home2-toggle-inner">
                    <input id="ce-toggle" checked type="checkbox" />
                    <span class="custom-toggle"></span>
                    <span class="t-month">Monthly</span>
                    <span class="t-year">Yearly (Save 20%)</span>
                  </div>
                  <p>Save up to 32% with yearly billing.</p>
                </div>
              </div>
            </div>
            <div class="space60"></div>
            <div class="tab-content">
              <div id="monthly">
                <div class="row">
                  <div class="col-md-6 col-lg-4">
                    <div class="pricing-box" data-aos="fade-up" data-aos-duration="1100">
                      <div class="pricing-box-single">
                        <div class="image">
                          <img src="/img/pricing/pricing-img1.png" alt="" />
                        </div>
                        <h4>Strater</h4>
                        <p>Search engine ranks effortlessly with our a powerful and intuitive best software eSoft</p>
                        <p class="span-headding">
                          $29 <span>/Billed Yearly</span>
                        </p>

                        <ul class="list">
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 Project
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 User
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 10 K clicks/month
                          </li>
                          <li class="crose">
                            <span><i class="fa-solid fa-xmark"></i></span> Download Data
                          </li>
                        </ul>

                        <div class="space20"></div>
                        <div class="button">
                          <a class="immiPressBtn" href="{{ route('second', ['auth', 'account']) }}"><span>Start Free Trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="pricing-box" data-aos="fade-up" data-aos-duration="900">
                      <div class="pricing-box-single">
                        <div class="image">
                          <img src="/img/pricing/pricing-img2.png" alt="" />
                        </div>
                        <h4>Business</h4>
                        <p>Search engine ranks effortlessly with our a powerful and intuitive best software eSoft</p>
                        <p class="span-headding">
                          $249 <span>/Billed Yearly</span>
                        </p>

                        <ul class="list">
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 Project
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 User
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 10 K clicks/month
                          </li>
                          <li class="crose">
                            <span><i class="fa-solid fa-xmark"></i></span> Download Data
                          </li>
                        </ul>

                        <div class="space20"></div>
                        <div class="button">
                          <a class="immiPressBtn" href="{{ route('second', ['auth', 'account']) }}"><span>Start Free Trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="pricing-box" data-aos="fade-up" data-aos-duration="1000">
                      <div class="pricing-box-single">
                        <div class="image">
                          <img src="/img/pricing/pricing-img3.png" alt="" />
                        </div>
                        <h4>Elite/ Enterprise</h4>
                        <p>Search engine ranks effortlessly with our a powerful and intuitive best software eSoft</p>
                        <p class="span-headding">
                          $499 <span>/Billed Yearly</span>
                        </p>

                        <ul class="list">
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 Project
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 User
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 10 K clicks/month
                          </li>
                          <li class="crose">
                            <span><i class="fa-solid fa-xmark"></i></span> Download Data
                          </li>
                        </ul>

                        <div class="space20"></div>
                        <div class="button">
                          <a class="immiPressBtn" href="{{ route('second', ['auth', 'account']) }}"><span>Start Free Trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="yearly" style="display:none;">
                <div class="row">
                  <div class="col-md-6 col-lg-4">
                    <div class="pricing-box">
                      <div class="pricing-box-single">
                        <div class="image">
                          <img src="/img/pricing/pricing-img1.png" alt="" />
                        </div>
                        <h4>Strater</h4>
                        <p>Search engine ranks effortlessly with our a powerful and intuitive best software eSoft</p>
                        <p class="span-headding">
                          $11 <span>/Billed Monthly</span>
                        </p>

                        <ul class="list">
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 Project
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 User
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 10 K clicks/month
                          </li>
                          <li class="crose">
                            <span><i class="fa-solid fa-xmark"></i></span> Download Data
                          </li>
                        </ul>

                        <div class="space20"></div>
                        <div class="button">
                          <a class="immiPressBtn" href="{{ route('second', ['auth', 'account']) }}"><span>Start Free Trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="pricing-box">
                      <div class="pricing-box-single">
                        <div class="image">
                          <img src="/img/pricing/pricing-img2.png" alt="" />
                        </div>
                        <h4>Business</h4>
                        <p>Search engine ranks effortlessly with our a powerful and intuitive best software eSoft</p>
                        <p class="span-headding">
                          $44 <span>/Billed Monthly</span>
                        </p>

                        <ul class="list">
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 Project
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 User
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 10 K clicks/month
                          </li>
                          <li class="crose">
                            <span><i class="fa-solid fa-xmark"></i></span> Download Data
                          </li>
                        </ul>

                        <div class="space20"></div>
                        <div class="button">
                          <a class="immiPressBtn" href="{{ route('second', ['auth', 'account']) }}"><span>Start Free Trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                    <div class="pricing-box">
                      <div class="pricing-box-single">
                        <div class="image">
                          <img src="/img/pricing/pricing-img3.png" alt="" />
                        </div>
                        <h4>Elite/ Enterprise</h4>
                        <p>Search engine ranks effortlessly with our a powerful and intuitive best software eSoft</p>
                        <p class="span-headding">
                          $78 <span>/Billed Monthly</span>
                        </p>

                        <ul class="list">
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 Project
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 1 User
                          </li>
                          <li>
                            <span><i class="fa-solid fa-check"></i></span> 10 K clicks/month
                          </li>
                          <li class="crose">
                            <span><i class="fa-solid fa-xmark"></i></span> Download Data
                          </li>
                        </ul>

                        <div class="space20"></div>
                        <div class="button">
                          <a class="immiPressBtn" href="{{ route('second', ['auth', 'account']) }}"><span>Start Free Trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
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
    <!-- ===== PRIICING AREA END ======= -->

    <!-- ===== CTA AREA START ======= -->
    <div class="cta3" style="background-image: url(/img/bg/cta3-bg.png); background-position: center center; background-size: cover; background-repeat: no-repeat;">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="headding3">
              <h2 data-aos="fade-left" data-aos-duration="900">Get started with eSoft today</h2>
              <div class="space16"></div>
              <p data-aos="fade-left" data-aos-duration="1100">Get 7 days of full access to all toolkits. Cancel anytime</p>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="cta3-subscribe" data-aos="fade-right" data-aos-duration="1100">
              <div class="input">
                <input type="email" placeholder="Enter your email" />
              </div>
              <div class="button">
                <button class="immiPressBtn"><span>Start your free trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ===== CTA AREA END ======= -->

    <!-- ===== BRAND LOGOS AREA START ======= -->
    <div class="brand-area3 sp">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 m-auto text-center">
            <div class="headding3">
              <h2 data-aos="fade-up" data-aos-duration="900">Elevate Your Brand with Our Solutions</h2>
              <div class="space10"></div>
              <p data-aos="fade-up" data-aos-duration="1100">Save time and effort with our automation features. Routine SEO tasks are streamlined, allowing you.</p>
              <div class="space20"></div>
              <div class="" data-aos="fade-up" data-aos-duration="900">
                <a href="{{ route('second', ['pages', 'features']) }}" class="learn">Explore Our Integration Library <span><i class="fa-regular fa-arrow-right"></i></span></a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="big-logo" data-tilt data-tilt-full-page-listening data-tilt-perspective="3000">
            <img src="/img/logo/brand-logo3.png" alt="" />
          </div>
        </div>
      </div>
    </div>
    <!-- ===== BRAND LOGOS AREA END ======= -->

    <!-- ===== TES AREA START ======= -->
    <div class="tes3 sp" id="tes" style="background-image: url(/img/bg/tes3-bg.png); background-position: center center; background-repeat: no-repeat; background-size: cover;">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="headding2">
              <h2 data-aos="fade-right" data-aos-duration="900">Why Our Users Love Us</h2>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="testimonial-arrows" data-aos="fade-left" data-aos-duration="1000">
              <button class="testimonial-prev-arrow2"><i class="fa-regular fa-arrow-left"></i></button>
              <button class="testimonial-next-arrow2"><i class="fa-regular fa-arrow-right"></i></button>
            </div>
          </div>
        </div>

        <div class="space60"></div>

        <div class="row">
          <div class="col-lg-12">
            <div class="tes2-slider-all" data-aos="fade-up" data-aos-duration="1000">
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
                  <img src="/img/icons/tes-icon3.png" alt="" />
                </div>

                <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

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
                  <img src="/img/icons/tes-icon3.png" alt="" />
                </div>

                <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

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
                  <img src="/img/icons/tes-icon3.png" alt="" />
                </div>

                <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

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
                  <img src="/img/icons/tes-icon3.png" alt="" />
                </div>

                <p>I can't imagine managing our email campaigns a without eSoft. The simplicity of creating visually stunning emails combined with powerful automation tools has been a game-changer for our marketing team. Our engagement rates have soared.</p>

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

    <!-- ===== BLOG AREA START ======= -->
    <div class="blog3 sp" id="blog">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="headding3">
              <h2 data-aos="fade-right" data-aos-duration="900">SEO Insights For 202X: Staying Ahead Of eSoft</h2>
              <div class="space16"></div>
              <p data-aos="fade-right" data-aos-duration="1100">Stay ahead of the game by gaining insights into your competitors' strategies. Our competitor analysis tools unveil their strengths an weaknesses, enabling you to refine your approach and outpace.</p>

              <div class="space30"></div>
              <div class="" data-aos="fade-right" data-aos-duration="1000">
                <a href="{{ route('second', ['blogs', 'blog']) }}" class="immiPressBtn"><span>Read more blog</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="blog-boxs">
              <div class="blog-single-box" data-aos="fade-up" data-aos-duration="1000">
                <div class="image">
                  <img src="/img/blog/blog3-img1.png" alt="" />
                </div>
                <div class="headding">
                  <h4><a href="{{ route('second', ['blogs', 'details']) }}">Digital Domination: SEO Strategies That Work</a></h4>
                  <p>SEO tasks are streamlined, allowing you to focus on strategic initiatives while.</p>
                  <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read more <span><i class="fa-regular fa-arrow-right"></i></span></a>
                </div>
              </div>

              <div class="blog-single-box" data-aos="fade-up" data-aos-duration="1100">
                <div class="image">
                  <img src="/img/blog/blog3-img1.png" alt="" />
                </div>
                <div class="headding">
                  <h4><a href="{{ route('second', ['blogs', 'details']) }}">Optimising Success: The Best SEO Playbook</a></h4>
                  <p>SEO tasks are streamlined, allowing you to focus on strategic initiatives while.</p>
                  <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Read more <span><i class="fa-regular fa-arrow-right"></i></span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ===== BLOG AREA END ======= -->
  </div>

  <!-- ===== CTA 2 AREA END ======= -->
  <div class="cta3-3" style="background-image: url(/img/bg/cta3-2.png); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto text-center">
          <div class="headding3">
            <h2>Join 500,000+ SEO's Who Trust eSoft For Insights That Help Their Business Grow.</h2>
            <div class="space16"></div>
            <p>eSoft has exceeded our expectations in every way. The ease with & which Search Engine we can target specific audience segments has an transform.</p>
            <div class="space20"></div>
            <div class="">
              <a href="{{ route('second', ['auth', 'account']) }}" class="immiPressBtn"><span>Start your free trail</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== CTA 2 AREA END ======= -->

  <!-- ===== FOOTER AREA START ======= -->
  <div class="footer3 _relative">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="subscribe-area" style="background-image: url(/img/bg/footer3-sebscribe-bg.png); background-position: center; background-size: cover; background-repeat: no-repeat;">
            <h5>Subscribe to our newsletter</h5>
            <p>eSoft has exceeded our expectations every way. The ease with & which Search Engine</p>
            <div class="input-area">
              <input type="email" placeholder="Email" />
              <div class="button">
                <button type="submit" class="immiPressBtn"><span>SSubscribe</span> <span class="arrow"><i class="fa-regular fa-arrow-right"></i></span></button>
              </div>
            </div>
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
            <h3>Product</h3>

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
              <a href="{{ route('any', 'index') }}"><img src="/img/logo/header-logo3.png" alt="" /></a>
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
