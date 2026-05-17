<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <meta name="description" content="Latest articles, updates, and insights.">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white antialiased">
    <header class="border-b border-white/10 bg-slate-950/90">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
            <a href="{{ url('/') }}" class="font-semibold">Titan CMS</a>
            <nav class="flex items-center gap-5 text-sm text-slate-300">
                <a href="{{ url('/') }}" class="hover:text-white">Home</a>
                <a href="{{ url('/blog') }}" class="text-white">Blog</a>
                <a href="{{ url('/contact') }}" class="hover:text-white">Contact</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-16">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-wide text-cyan-300">Blog</p>
            <h1 class="mt-3 text-5xl font-bold tracking-tight">Latest articles</h1>
            <p class="mt-5 text-lg text-slate-300">Published from Tomato CMS and surfaced through the custom site CMS.</p>
        </div>

        @if(! $stats['available'])
            <div class="mt-10 rounded-2xl border border-amber-400/30 bg-amber-400/10 p-6 text-amber-100">
                Tomato CMS tables were not found yet. Run the Tomato CMS migrations, then publish posts from Admin → Site CMS → Blog Posts.
            </div>
        @endif

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($posts as $post)
                <article class="rounded-3xl border border-white/10 bg-white/[.04] p-6 transition hover:-translate-y-1 hover:border-cyan-300/50">
                    @if($post->cms_image)
                        <img src="{{ str_starts_with((string) $post->cms_image, 'http') ? $post->cms_image : asset('storage/'.$post->cms_image) }}" alt="" class="mb-5 h-44 w-full rounded-2xl object-cover">
                    @endif
                    <h2 class="text-2xl font-semibold"><a href="{{ url('/blog/'.$post->cms_slug) }}" class="hover:text-cyan-200">{{ $post->cms_title }}</a></h2>
                    @if($post->cms_excerpt)
                        <p class="mt-3 text-sm text-slate-300">{{ $post->cms_excerpt }}</p>
                    @endif
                    <a href="{{ url('/blog/'.$post->cms_slug) }}" class="mt-5 inline-flex text-sm font-semibold text-cyan-300">Read article →</a>
                </article>
            @empty
                <div class="rounded-2xl border border-white/10 bg-white/[.04] p-6 text-slate-300 md:col-span-2 lg:col-span-3">
                    No published blog posts yet.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </main>
</body>
</html>
