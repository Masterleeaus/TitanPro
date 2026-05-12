@extends('layouts.app')
@section('page-title')
    {{ __('Titan Docs') }}
@endsection
@section('page-breadcrumb')
{{ __('Titan Docs') }}
@endsection
@section('page-action')

@endsection
@push('css')
<style>
    .template-list-div:hover
    {
    -webkit-transform: translateY(-5px);
    -ms-transform: translateY(-5px);
    transform: translateY(-5px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.32);
    cursor: pointer;
    border-radius: 10px;
    }
</style>
@endpush
@section('content')

@if(isset($wizard) && $wizard)
    @php $w = $wizard->payload_json ?? []; @endphp
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1">{{ __('Wizard Ready') }}</h4>
                            <div class="text-muted">
                                {{ __('Kind') }}: <span class="fw-semibold">{{ $wizard->doc_kind === 'swms' ? __('SWMS') : __('Document') }}</span>
                                &nbsp;•&nbsp;
                                {{ __('Type') }}: <span class="fw-semibold">{{ data_get($w, 'step_1.doc_type', '-') }}</span>
                                &nbsp;•&nbsp;
                                {{ __('Standards') }}: <span class="fw-semibold">{{ (string) data_get($w, 'step_3.want_official_standards', '0') === '1' ? __('Yes') : __('No') }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-light" href="{{ route('titan.docs.generator.step', ['session' => $wizard->id, 'step' => 1]) }}">{{ __('Edit Wizard') }}</a>
                            <a class="btn btn-primary" href="{{ route('titan.docs.generator.review', ['session' => $wizard->id]) }}">{{ __('Review') }}</a>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        {{ __('Next: choose a template below, then generate. The wizard details will be used as your structured context.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-xxl-12 ">
        <div class="card border-0">

            <div class="card-body pt-2 favorite-templates-panel">

                <ul class="nav nav-pills pt-2 gap-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">{{ __('All') }}</button>
                    </li>
                    @if (count($content)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab" aria-controls="content" aria-selected="false">{{ __('Content') }}</button>
                        </li>
                    @endif
                    @if (count($blog)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog" type="button" role="tab" aria-controls="blog" aria-selected="false">{{ __('Blog') }}</button>
                        </li>
                    @endif
                    @if (count($website)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="website-tab" data-bs-toggle="tab" data-bs-target="#website" type="button" role="tab" aria-controls="website" aria-selected="false">{{ __('Website') }}</button>
                        </li>
                    @endif
                    @if (count($social)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="social-media-tab" data-bs-toggle="tab" data-bs-target="#social-media" type="button" role="tab" aria-controls="social-media" aria-selected="false">{{ __('Social Media') }}</button>
                        </li>
                    @endif
                    @if (count($email)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">{{ __('Email') }}</button>
                        </li>
                    @endif
                    @if (count($video)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="video-tab" data-bs-toggle="tab" data-bs-target="#video" type="button" role="tab" aria-controls="video" aria-selected="false">{{ __('Video') }}</button>
                        </li>
                    @endif

                    @if (count($other)>0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button" role="tab" aria-controls="other" aria-selected="false">{{ __('Other') }}</button>
                        </li>
                    @endif
                </ul>


                <div class="tab-content pt-4" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($template as $template)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div" onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card " >
                                            <div class="card-body">
                                                <div class="template-icon mb-2" >
                                                    <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }
                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2"> {{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade " id="content" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($content as $template)
                            @if($template->category_id==1)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div" onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card">
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                     <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade " id="blog" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($blog as $template)
                            @if($template->category_id==2)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div"  onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card ">
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                    <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade " id="website" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($website as $template)
                            @if($template->category_id==3)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div"  onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card" >
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                    <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade " id="social-media" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($social as $template)
                            @if($template->category_id==4)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div"  onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card">
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                     <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                     <div class="tab-pane fade " id="email" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($email as $template)
                            @if($template->category_id==6)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div" onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card ">
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                     <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade " id="video" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($video as $template)
                            @if($template->category_id==5)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div" onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card" >
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                    <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade " id="other" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row" id="templates-panel">
                            @foreach ($other as $template)
                            @if($template->category_id==7)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="template template-list-div" onclick="document_create(<?=$template->id?>,0)">
                                        <div class="card">
                                            <div class="card-body pt-5">
                                                <div class="template-icon mb-4">
                                                     <?php
                                                        if(file_exists('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon)){
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/'.$template->icon);
                                                        }
                                                        else
                                                        {
                                                            $path = asset('Modules/TitanDocs/Resources/assets/upload/template-icon/book.png');
                                                        }


                                                    ?>
                                                    <img src=" {{ $path }} ">
                                                </div>
                                                <div class="template-title">
                                                    <h6 class="mb-2 fs-15 number-font">{{$template->name}}</h6>
                                                </div>
                                                <div class="template-info">
                                                    <p class="fs-13 text-muted mb-2">{{ Str::limit($template->description, 80) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    function document_create(id,is_custom) {
        var token= $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ url("/aidocument/store")}}',
            method:"POST",
            data: {"_token": token,'template_id':id,'is_custom':is_custom},
            success: function(data) {
                if(data.status==1)
                {
                    window.location.replace(data.URL);
                }
                else
                {
                    toastrs('Error', data.error, 'error');
                }

            }
        });
    }
</script>
@endpush
