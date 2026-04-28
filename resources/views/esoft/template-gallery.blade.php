@extends('esoft.layouts.base', ['title' => 'eSoft Template Library', 'logo5' => true])

@section('body_attribute')
  class="body"
@endsection

@section('content')
  <section style="min-height:100vh;background:#050816;color:#fff;padding:72px 24px;font-family:Inter,Arial,sans-serif;">
    <div style="max-width:1180px;margin:0 auto;">
      <div style="text-align:center;margin-bottom:48px;">
        <span style="display:inline-block;border:1px solid rgba(96,165,250,.5);border-radius:999px;padding:8px 18px;color:#60a5fa;margin-bottom:18px;">eSoft Template Pack</span>
        <h1 style="font-size:clamp(42px,7vw,86px);line-height:.95;font-weight:900;margin:0 0 20px;">Choose a homepage template</h1>
        <p style="font-size:20px;color:#cbd5e1;max-width:760px;margin:0 auto;">This page is only the gallery. Every card below opens a separate original eSoft Blade template.</p>
      </div>

      @php
        $groups = [
          'Homepage demos' => [
            ['Home 1', 'template-esoft-home-1'], ['Home 2', 'template-esoft-home-2'], ['Home 3', 'template-esoft-home-3'], ['Home 4', 'template-esoft-home-4'], ['Home 5', 'template-esoft-home-5'], ['Home 6', 'template-esoft-home-6'], ['Home 7', 'template-esoft-home-7'], ['Home 8', 'template-esoft-home-8'], ['Home 9', 'template-esoft-home-9'],
          ],
          'Single-page variants' => [
            ['Single 1', 'template-esoft-single-1'], ['Single 2', 'template-esoft-single-2'], ['Single 3', 'template-esoft-single-3'], ['Single 4', 'template-esoft-single-4'], ['Single 5', 'template-esoft-single-5'], ['Single 6', 'template-esoft-single-6'], ['Single 7', 'template-esoft-single-7'], ['Single 8', 'template-esoft-single-8'], ['Single 9', 'template-esoft-single-9'],
          ],
          'Inner pages' => [
            ['About', 'template-esoft-about'], ['Features', 'template-esoft-features'], ['Pricing', 'template-esoft-pricing'], ['Contact', 'template-esoft-contact'], ['Download', 'template-esoft-download'], ['Testimonials', 'template-esoft-testimonial'], ['Blog', 'template-esoft-blog'], ['Blog Detail', 'template-esoft-blog-details'], ['Login', 'template-esoft-login'], ['Account', 'template-esoft-account'],
          ],
        ];
      @endphp

      @foreach($groups as $heading => $items)
        <div style="margin:42px 0 20px;">
          <h2 style="font-size:28px;font-weight:800;margin:0 0 18px;">{{ $heading }}</h2>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
            @foreach($items as [$label, $slug])
              <a href="{{ url('/pages/'.$slug) }}" style="display:block;text-decoration:none;color:#fff;background:rgba(15,23,42,.82);border:1px solid rgba(148,163,184,.25);border-radius:18px;padding:22px;box-shadow:0 12px 30px rgba(0,0,0,.18);">
                <strong style="font-size:20px;display:block;margin-bottom:8px;">{{ $label }}</strong>
                <span style="color:#93c5fd;font-size:14px;">Open preview →</span>
              </a>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection
