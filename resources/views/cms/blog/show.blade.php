<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->cms_title }}</title>
    @if($post->cms_excerpt)<meta name="description" content="{{ $post->cms_excerpt }}">@endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white antialiased">
    <header class="border-b border-white/10 bg-slate-950/90">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
            <a href="{{ url('/') }}" class="font-semibold">Titan CMS</a>
            <nav class="flex items-center gap-5 text-sm text-slate-300">
                <a href="{{ url('/') }}" class="hover:text-white">Home</a>
                <a href="{{ url('/blog') }}" class="hover:text-white">Blog</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-6 py-16">
        <a href="{{ url('/blog') }}" class="text-sm font-semibold text-cyan-300">← Back to blog</a>
        <article class="mt-8">
            <h1 class="text-5xl font-bold tracking-tight">{{ $post->cms_title }}</h1>
            @if($post->cms_excerpt)
                <p class="mt-5 text-xl text-slate-300">{{ $post->cms_excerpt }}</p>
            @endif
            @if($post->cms_image)
                <img src="{{ str_starts_with((string) $post->cms_image, 'http') ? $post->cms_image : asset('storage/'.$post->cms_image) }}" alt="" class="mt-10 max-h-[520px] w-full rounded-3xl object-cover">
            @endif
            <div class="prose prose-invert prose-cyan mt-10 max-w-none text-slate-200">
                {!! $post->cms_body !!}
            </div>
        </article>
    </main>
</body>
</html>
