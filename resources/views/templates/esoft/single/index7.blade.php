@extends('templates.esoft.layouts.base', ['title' => 'Management Landing Page Software'])

@section('body_attribute')
  class="body1 body"
@endsection

@section('content')
  @include('templates.esoft.layouts.partials.loader', ["isLight" => true])
  
  @include('templates.esoft.layouts.partials.header-single.navbar7')

  <!-- ===== HERO AREA START ======= -->
  <div class="hero-area1" style="background-image: url(/img/bg/hero1-bg.png);">
    <div class="container">
    <div class="row">
      <div class="col-lg-7 m-auto text-center">
      <div class="main-headding">
        <span class="span"><img src="/img/shapes/headding1-span.png" alt="" /> Get everyone working in a single platform</span>
        <h1 class="pbmit-title">The Ultimate Of <span class="after">eSoft</span> Project Management</h1>
        <p>Our intuitive platform is designed to streamline your workflow, offering a comprehensive suite of tools a simplify every aspect of project oversight. From seamless task manage.</p>
        <div class="space30"></div>
        <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn--ripple ripple">Get Started For Free</a>
        <a href="{{ route('second', ['pages', 'features']) }}" class="btn btn2 btn--ripple ripple">Book A Demo</a>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== HERO AREA END ======= -->

  <!-- ===== HERO AREA IMAGE START ======= -->
  <div class="hero-area-image">
    <div class="container">
    <div class="row">
      <div class="col-lg-10 m-auto text-center">
      <div class="images-area _relative">
        <div class="image">
        <img src="/img/hero/hero1-image.png" alt="" />
        </div>
        <img src="/img/shapes/hero1-img-shape.png" alt="" class="shape" />
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== HERO AREA IMAGE END ======= -->

  <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0">
    <!-- ===== FEATURES AREA START ======= -->
    <div class="features sp _relative" id="features">
    <div class="container">
      <div class="row">
      <div class="col-lg-7 m-auto text-center">
        <div class="headding1">
        <span class="span" data-aos="fade-up" data-aos-duration="800"><img src="/img/shapes/headding1-span.png" alt="" /> Get everyone working in a single platform</span>
        <h2 data-aos="fade-up" data-aos-duration="1000">Easily customize <span class="after">eSoft</span> as <br /> team needs grow</h2>
        </div>
        <div class="space40"></div>

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" data-aos="fade-up" data-aos-duration="1100">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="pills-item-tab1" data-bs-toggle="pill" data-bs-target="#pills-item1" type="button" role="tab" aria-controls="pills-item1" aria-selected="true">Portal Editor</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-item2-tab" data-bs-toggle="pill" data-bs-target="#pills-item2" type="button" role="tab" aria-controls="pills-item2" aria-selected="false">Client Portals</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-item3-tab" data-bs-toggle="pill" data-bs-target="#pills-item3" type="button" role="tab" aria-controls="pills-item3" aria-selected="false">Product</button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-item4-tab" data-bs-toggle="pill" data-bs-target="#pills-item4" type="button" role="tab" aria-controls="pills-item4" aria-selected="false">Team Workspace</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-item5-tab" data-bs-toggle="pill" data-bs-target="#pills-item5" type="button" role="tab" aria-controls="pills-item5" aria-selected="false">Agencies</button>
        </li>
        </ul>
      </div>
      </div>

      <div class="space40"></div>
      <div class="row">
      <div class="col-lg-12">
        <div class="tab-content" data-aos="fade-up" data-aos-duration="900" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-item1" role="tabpanel" aria-labelledby="pills-item-tab1">
          <div class="features-item-box">
          <div class="features-box">
            <div class="row">
            <div class="col-lg-7">
              <div class="headding1">
              <h5>Complete Team Workspace Solution</h5>
              <p>
                The ultimate solution for seamless project management, eSoft <br /> revolutionizes the way you manage task projects.
              </p>
              <div class="space20"></div>
              <ul class="tab-list">
                <li>
                <span><i class="fa-regular fa-check"></i></span> Visualize projects, progress, and teamwork
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Speed up collaboration and feedback cycles
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Optimize resources and productivity
                </li>
              </ul>
              <div class="space30"></div>
              <div class="bottom-headding-area">
                <div class="image">
                <img src="/img/features/features-img1.png" alt="" />
                </div>
                <div class="">
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
                <p class="pera">
                  Team decreased project delay by over <br /> 70% throughout first year using eSoft
                </p>
                </div>
              </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="features-sidebox-area">
              <div class="features-sidebox">
                <ul>
                <li>
                  <img src="/img/icons/features-icon1.png" alt="" /> Flexible workflows for any project
                </li>
                <li>
                  <img src="/img/icons/features-icon2.png" alt="" /> Tasks, docs, spreadsheets, & more
                </li>
                <li>
                  <img src="/img/icons/features-icon3.png" alt="" /> Resource and workload optimization
                </li>
                <li>
                  <img src="/img/icons/features-icon4.png" alt="" /> Dashboards and insights
                </li>
                </ul>
              </div>
              <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn--ripple ripple">Join Workspace With eSoft</a>
              </div>
            </div>
            </div>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="pills-item2" role="tabpanel" aria-labelledby="pills-item2-tab">
          <div class="features-item-box">
          <div class="features-box">
            <div class="row">
            <div class="col-lg-7">
              <div class="headding1">
              <h5>Complete Team Workspace Solution</h5>
              <p>
                The ultimate solution for seamless project management, eSoft <br /> revolutionizes the way you manage task projects.
              </p>
              <div class="space20"></div>
              <ul class="tab-list">
                <li>
                <span><i class="fa-regular fa-check"></i></span> Visualize projects, progress, and teamwork
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Speed up collaboration and feedback cycles
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Optimize resources and productivity
                </li>
              </ul>
              <div class="space30"></div>
              <div class="bottom-headding-area">
                <div class="image">
                <img src="/img/features/features-img1.png" alt="" />
                </div>
                <div class="">
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
                <p class="pera">
                  Team decreased project delay by over <br /> 70% throughout first year using eSoft
                </p>
                </div>
              </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="features-sidebox-area">
              <div class="features-sidebox">
                <ul>
                <li>
                  <img src="/img/icons/features-icon1.png" alt="" /> Flexible workflows for any project
                </li>
                <li>
                  <img src="/img/icons/features-icon2.png" alt="" /> Tasks, docs, spreadsheets, & more
                </li>
                <li>
                  <img src="/img/icons/features-icon3.png" alt="" /> Resource and workload optimization
                </li>
                <li>
                  <img src="/img/icons/features-icon4.png" alt="" /> Dashboards and insights
                </li>
                </ul>
              </div>
              <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn--ripple ripple">Join Workspace With eSoft</a>
              </div>
            </div>
            </div>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="pills-item3" role="tabpanel" aria-labelledby="pills-item3-tab">
          <div class="features-item-box">
          <div class="features-box">
            <div class="row">
            <div class="col-lg-7">
              <div class="headding1">
              <h5>Complete Team Workspace Solution</h5>
              <p>
                The ultimate solution for seamless project management, eSoft <br /> revolutionizes the way you manage task projects.
              </p>
              <div class="space20"></div>
              <ul class="tab-list">
                <li>
                <span><i class="fa-regular fa-check"></i></span> Visualize projects, progress, and teamwork
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Speed up collaboration and feedback cycles
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Optimize resources and productivity
                </li>
              </ul>
              <div class="space30"></div>
              <div class="bottom-headding-area">
                <div class="image">
                <img src="/img/features/features-img1.png" alt="" />
                </div>
                <div class="">
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
                <p class="pera">
                  Team decreased project delay by over <br /> 70% throughout first year using eSoft
                </p>
                </div>
              </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="features-sidebox-area">
              <div class="features-sidebox">
                <ul>
                <li>
                  <img src="/img/icons/features-icon1.png" alt="" /> Flexible workflows for any project
                </li>
                <li>
                  <img src="/img/icons/features-icon2.png" alt="" /> Tasks, docs, spreadsheets, & more
                </li>
                <li>
                  <img src="/img/icons/features-icon3.png" alt="" /> Resource and workload optimization
                </li>
                <li>
                  <img src="/img/icons/features-icon4.png" alt="" /> Dashboards and insights
                </li>
                </ul>
              </div>
              <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn--ripple ripple">Join Workspace With eSoft</a>
              </div>
            </div>
            </div>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="pills-item4" role="tabpanel" aria-labelledby="pills-item4-tab">
          <div class="features-item-box">
          <div class="features-box">
            <div class="row">
            <div class="col-lg-7">
              <div class="headding1">
              <h5>Complete Team Workspace Solution</h5>
              <p>
                The ultimate solution for seamless project management, eSoft <br /> revolutionizes the way you manage task projects.
              </p>
              <div class="space20"></div>
              <ul class="tab-list">
                <li>
                <span><i class="fa-regular fa-check"></i></span> Visualize projects, progress, and teamwork
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Speed up collaboration and feedback cycles
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Optimize resources and productivity
                </li>
              </ul>
              <div class="space30"></div>
              <div class="bottom-headding-area">
                <div class="image">
                <img src="/img/features/features-img1.png" alt="" />
                </div>
                <div class="">
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
                <p class="pera">
                  Team decreased project delay by over <br /> 70% throughout first year using eSoft
                </p>
                </div>
              </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="features-sidebox-area">
              <div class="features-sidebox">
                <ul>
                <li>
                  <img src="/img/icons/features-icon1.png" alt="" /> Flexible workflows for any project
                </li>
                <li>
                  <img src="/img/icons/features-icon2.png" alt="" /> Tasks, docs, spreadsheets, & more
                </li>
                <li>
                  <img src="/img/icons/features-icon3.png" alt="" /> Resource and workload optimization
                </li>
                <li>
                  <img src="/img/icons/features-icon4.png" alt="" /> Dashboards and insights
                </li>
                </ul>
              </div>
              <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn--ripple ripple">Join Workspace With eSoft</a>
              </div>
            </div>
            </div>
          </div>
          </div>
        </div>
        <div class="tab-pane fade" id="pills-item5" role="tabpanel" aria-labelledby="pills-item5-tab">
          <div class="features-item-box">
          <div class="features-box">
            <div class="row">
            <div class="col-lg-7">
              <div class="headding1">
              <h5>Complete Team Workspace Solution</h5>
              <p>
                The ultimate solution for seamless project management, eSoft <br /> revolutionizes the way you manage task projects.
              </p>
              <div class="space20"></div>
              <ul class="tab-list">
                <li>
                <span><i class="fa-regular fa-check"></i></span> Visualize projects, progress, and teamwork
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Speed up collaboration and feedback cycles
                </li>
                <li>
                <span><i class="fa-regular fa-check"></i></span> Optimize resources and productivity
                </li>
              </ul>
              <div class="space30"></div>
              <div class="bottom-headding-area">
                <div class="image">
                <img src="/img/features/features-img1.png" alt="" />
                </div>
                <div class="">
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
                <p class="pera">
                  Team decreased project delay by over <br /> 70% throughout first year using eSoft
                </p>
                </div>
              </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="features-sidebox-area">
              <div class="features-sidebox">
                <ul>
                <li>
                  <img src="/img/icons/features-icon1.png" alt="" /> Flexible workflows for any project
                </li>
                <li>
                  <img src="/img/icons/features-icon2.png" alt="" /> Tasks, docs, spreadsheets, & more
                </li>
                <li>
                  <img src="/img/icons/features-icon3.png" alt="" /> Resource and workload optimization
                </li>
                <li>
                  <img src="/img/icons/features-icon4.png" alt="" /> Dashboards and insights
                </li>
                </ul>
              </div>
              <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn--ripple ripple">Join Workspace With eSoft</a>
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

    <img class="shape1 shape-animaiton1" src="/img/shapes/features-shape1.png" alt="" />
    <img class="shape2 shape-animaiton1" src="/img/shapes/features-shape2.png" alt="" />
    </div>
    <!-- ===== FEATURES AREA END ======= -->

    <!-- ===== WORK PROSESS AREA START ======= -->
    <div class="work-prosess pb120 _relative" id="work" style="background-image: url(/img/bg/work-prosess-bg.png); background-repeat: no-repeat; background-size: contain;">
    <div class="container">
      <div class="row">
      <div class="col-lg-7 m-auto text-center">
        <div class="headding1">
        <span class="span" data-aos="fade-up" data-aos-duration="800"><img src="/img/shapes/headding1-span.png" alt="" /> Our Work prosess</span>
        <h2 data-aos="fade-up" data-aos-duration="1000">One platform to manage <span class="after">work</span></h2>
        </div>
      </div>
      </div>

      <div class="space30"></div>
      <div class="row">
      <div class="col-lg-6">
        <div class="work-box-area left-top">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="800">
          <div class="headding1">
          <h4><a href="#">Al-Powered Productivity</a></h4>
          <div class="space16"></div>
          <p>With an intuitive interface and powerful features, our platform simplifies collaboration, allowing you to effortlessly delegate.</p>
          </div>
          <div class="space40"></div>
          <div class="image">
          <img src="/img/work/work-prosess-img1.png" alt="" />
          </div>
        </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="work-box-area right-top">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="1000">
          <div class="headding1">
          <h4><a href="#">View Work Your Way</a></h4>
          <div class="space16"></div>
          <p>Team's project management experience. Our robust suite & offers a unified hub an task allocation, collaboration, project.</p>
          </div>
          <div class="space40"></div>
          <div class="image">
          <img src="/img/work/work-prosess-img2.png" alt="" />
          </div>
        </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="work-box-area right-top">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="900">
          <div class="headding1">
          <h4><a href="#">Search Anythings</a></h4>
          <div class="space16"></div>
          <p>With integrated file sharing & version control, your team a access manage</p>
          </div>
          <div class="space40"></div>
          <div class="image">
          <img src="/img/work/work-prosess-img3.png" alt="" />
          </div>
        </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="work-box-area left-bottom">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="1200">
          <div class="headding1">
          <h4><a href="#">Team Collaboration</a></h4>
          <div class="space16"></div>
          <p>Our platform brings together task an boards, share calendars, document.</p>
          </div>
          <div class="space40"></div>
          <div class="image">
          <img src="/img/work/work-prosess-img4.png" alt="" />
          </div>
        </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="work-box-area right-bottom">
        <div class="single-box" data-aos="zoom-in-up" data-aos-duration="1200">
          <div class="headding1">
          <h4><a href="#">Customized In Click</a></h4>
          <div class="space16"></div>
          <p>Empower your projects with a holistic workspace solution that a simplifies.</p>
          </div>
          <div class="space40"></div>
          <div class="image">
          <img src="/img/work/work-prosess-img5.png" alt="" />
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== WORK PROSESS AREA END ======= -->

    <!-- ===== TEAM AREA START ======= -->
    <div class="team-collaboration pb120" id="team">
    <div class="container">
      <div class="row align-items-center">
      <div class="col-lg-5">
        <div class="headding1">
        <span class="span" data-aos="fade-right" data-aos-duration="800"><img src="/img/shapes/headding1-span.png" alt="" /> Team Collaboration</span>
        <h2 data-aos="fade-right" data-aos-duration="1000">Mange your schedule & <br /> get booked hassle <span class="after">free</span></h2>
        <div class="space16"></div>
        <p data-aos="fade-right" data-aos-duration="1100">
          With an intuitive interface and powerful features, our platform <br /> simplifies collaboration, allowing you to effortlessly delegate.
        </p>

        <div class="team-boxs" data-aos="fade-right" data-aos-duration="800">
          <div class="space20"></div>
          <div class="row">
          <div class="col-md-6">
            <div class="single-box">
            <div class="icon">
              <img src="/img/icons/team-icon1.png" alt="" />
            </div>
            <div class="pera">
              <p>Project</p>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="single-box">
            <div class="icon">
              <img src="/img/icons/team-icon2.png" alt="" />
            </div>
            <div class="pera">
              <p>Invoice</p>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="single-box">
            <div class="icon">
              <img src="/img/icons/team-icon3.png" alt="" />
            </div>
            <div class="pera">
              <p>Proposals</p>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="single-box">
            <div class="icon">
              <img src="/img/icons/team-icon4.png" alt="" />
            </div>
            <div class="pera">
              <p>Forms</p>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="single-box">
            <div class="icon">
              <img src="/img/icons/team-icon5.png" alt="" />
            </div>
            <div class="pera">
              <p>Scheduler</p>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="single-box">
            <div class="icon">
              <img src="/img/icons/team-icon6.png" alt="" />
            </div>
            <div class="pera">
              <p>Inbox</p>
            </div>
            </div>
          </div>
          </div>
        </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="team-all-images">
        <div class="image1" data-aos="zoom-out" data-aos-duration="900">
          <img src="/img/team/team-img1.png" alt="" />
        </div>
        <div class="image2 shape-animaiton2" data-aos="flip-right" data-aos-duration="800">
          <img src="/img/team/team-img2.png" alt="" />
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== TEAM AREA END ======= -->

    <!-- ===== OTHERS AREA START ======= -->
    <div class="cta-area">
    <div class="container">
      <div class="row">
      <div class="col-lg-12 text-center">
        <div class="cta-area-bg">
        <div class="cta-smoll-bg">
          <div class="headding1">
          <h2 data-aos="fade-up" data-aos-duration="800">Bring teams & work together</h2>
          <div class="space16"></div>
          <p data-aos="fade-up" data-aos-duration="1000">Eliminate app chaos. Consolidate tools and cut costs.</p>

          <div class="space20"></div>
          <div class="buttons" data-aos="fade-up" data-aos-duration="1100">
            <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn5 btn--ripple ripple">Get Started Now</a>
            <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn3 btn--ripple ripple">14 Days Free Trials</a>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== OTHERS AREA END ======= -->

    <!-- ===== BRANS AREA START ======= -->
    <div class="brands _relative sp">
    <div class="container">
      <div class="row">
      <div class="col-lg-6 m-auto text-center">
        <div class="headding1">
        <span class="span" data-aos="fade-up" data-aos-duration="800"><img src="/img/shapes/headding1-span.png" alt="" /> Team Collaboration</span>
        <h2 data-aos="fade-up" data-aos-duration="1000">Connect entire <span class="after">workflows</span></h2>
        <div class="space16"></div>
        <p data-aos="fade-up" data-aos-duration="900">Our intuitive platform is designed to streamline your workflow, offering a comprehensive suite of tools a simplify every aspect of project oversight.</p>

        <h6 data-aos="fade-up" data-aos-duration="1100">Explore Our <a href="{{ route('second', ['pages', 'features']) }}" class="after">Integration Library</a></h6>
        </div>
      </div>
      </div>

      <div class="row">
      <div class="col-lg-12">
        <div class="brands-image" data-tilt data-tilt-full-page-listening data-tilt-perspective="3000">
        <img src="/img/logo/brands-logo.png" alt="" />
        </div>
      </div>
      </div>
    </div>
    <img class="brand-shape1 shape-animaiton2" src="/img/shapes/brand-shape1.png" alt="" />
    <img class="brand-shape2 shape-animaiton1" src="/img/shapes/brand-shape1.png" alt="" />
    <img class="brand-shape3" src="/img/shapes/brand-shape1.png" alt="" />
    </div>
    <!-- ===== BRANS AREA END ======= -->

    <!-- ===== TESTIMONIAL AREA START ======= -->
    <div class="testimonial-area pb120" id="tes">
    <div class="container">
      <div class="row">
      <div class="col-lg-6 m-auto text-center">
        <div class="headding1">
        <span class="span" data-aos="fade-up" data-aos-duration="800"><img src="/img/shapes/headding1-span.png" alt="" /> Client Testimonials</span>
        <h2 data-aos="fade-up" data-aos-duration="1000">Loved by industry <span class="after">leaders</span></h2>
        </div>
      </div>
      </div>

      <div class="space60"></div>
      <div class="row">
      <div class="col-lg-12">
        <div class="testimonial-slider-all" data-aos="fade-up" data-aos-duration="1100">
        <div class="testimonial-single-slider">
          <div class="tes1-brads-slider">
          <div class="single">
            <img src="/img/logo/tes1-logo1.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo2.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo3.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo4.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo5.png" alt="" />
          </div>

          <div class="single">
            <img src="/img/logo/tes1-logo1.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo2.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo3.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo4.png" alt="" />
          </div>
          <div class="single">
            <img src="/img/logo/tes1-logo5.png" alt="" />
          </div>
          </div>
          <div class="tes1-big-slider">
          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
              </div>
            </div>
            </div>
          </div>

          <div class="single-slider">
            <div class="row">
            <div class="col-lg-8">
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
              <p class="pera">“The team at eSoft not only delivered what was promised but also provided valuable insights that added immense value to our project, on your needs an Based on your needs, we devise. The support team is exceptional! They've been responsive and helpful at every step.”</p>

              <div class="bottom-headding">
              <h4><a href="#">Oliver Grioud/CEO, Lava Ltd</a></h4>
              <p>Read More Testimonials Feedback</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="image">
              <img src="/img/testimonial/tes1-big-img.png" alt="" />
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
      </div>
      </div>
    </div>
    </div>
    <!-- ===== TESTIMONIAL AREA END ======= -->

    <!-- ===== BLOG AREA START ======= -->
    <div class="blog1 pb120" id="blog">
    <div class="container">
      <div class="row">
      <div class="col-lg-6 m-auto text-center">
        <div class="headding1">
        <span class="span" data-aos="fade-up" data-aos-duration="800"><img src="/img/shapes/headding1-span.png" alt="" /> Our Blogs</span>
        <h2 data-aos="fade-up" data-aos-duration="1000">Our latest blog & <span class="after">news</span></h2>
        </div>
      </div>
      </div>

      <div class="space30"></div>
      <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="800">
        <div class="blog-image">
          <img src="/img/blog/blog-img1.png" alt="" />
        </div>
        <div class="headding1">
          <a href="#" class="date"><img src="/img/icons/date-icon.png" alt="" /> Mon Nov 01 2023</a>
          <h4><a href="{{ route('second', ['blogs', 'details']) }}">From Vision to Reality: Our Proven Project Management Approach</a></h4>
          <div class="space16"></div>
          <p>We begin by comprehensively understand your best project goals and requirements.</p>
          <div class="space16"></div>
          <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Learn more <span><i class="fa-regular fa-arrow-right"></i></span></a>
        </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="blog-box active" data-aos="zoom-in-up" data-aos-duration="900">
        <div class="blog-image">
          <img src="/img/blog/blog-img2.png" alt="" />
        </div>
        <div class="headding1">
          <a href="#" class="date"><img src="/img/icons/date-icon.png" alt="" /> Mon Nov 01 2023</a>
          <h4><a href="{{ route('second', ['blogs', 'details']) }}">From Vision to Reality: Our Proven Project Management Approach</a></h4>
          <div class="space16"></div>
          <p>We begin by comprehensively understand your best project goals and requirements.</p>
          <div class="space16"></div>
          <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Learn more <span><i class="fa-regular fa-arrow-right"></i></span></a>
        </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="1100">
        <div class="blog-image">
          <img src="/img/blog/blog-img3.png" alt="" />
        </div>
        <div class="headding1">
          <a href="#" class="date"><img src="/img/icons/date-icon.png" alt="" /> Thu Dec 02 2023</a>
          <h4><a href="{{ route('second', ['blogs', 'details']) }}">Navigate Success: Empowering Projects Expert Management</a></h4>
          <div class="space16"></div>
          <p>We begin by comprehensively understand your best project goals and requirements.</p>
          <div class="space16"></div>
          <a href="{{ route('second', ['blogs', 'details']) }}" class="learn">Learn more <span><i class="fa-regular fa-arrow-right"></i></span></a>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
    <!-- ===== BLOG AREA END ======= -->
  </div>

  <!-- ===== CTA AREA START ======= -->
  <div class="cta1" style="background-image: url(/img/bg/cta1-bg.png); background-position: center; background-size: cover; background-repeat: no-repeat;">
    <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
      <div class="headding1">
        <div class="big-logo">
        <img src="/img/logo/big-logo.png" alt="" />
        </div>
        <div class="space30"></div>
        <h2>Organize anything with an anyone, anywhere</h2>
        <div class="space16"></div>
        <p>Get your teams working together more closely, even if they’re far apart. Centralize project-related communications in one.</p>
        <div class="space20"></div>
        <div class="buttons">
        <a href="{{ route('second', ['auth', 'account']) }}" class="btn btn3 btn--ripple ripple">Get started -free forever</a>
        <a href="#" class="btn btn2 btn--ripple ripple"><img src="/img/icons/google.png" alt="" /> Sign up with google</a>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- ===== CTA AREA END ======= -->

  <!-- ===== FOOTER AREA START ======= -->
  <div class="footer1 _relative">
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
      <div class="col-lg-6">
        <div class="logo">
        <a href="{{ route('any', 'index') }}"><img src="/img/logo/header-logo1.png" alt="" /></a>
        </div>
      </div>
      <div class="col-lg-6">
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

    <img class="footer-shape" src="/img/shapes/footer-shape.png" alt="" />
  </div>
  <!-- ===== FOOTER AREA END ======= -->
@endsection