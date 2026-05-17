<x-filament-panels::page>
    <div class="min-h-[72vh] overflow-hidden rounded-3xl border border-cyan-500/20 bg-slate-950 text-slate-100 shadow-2xl shadow-cyan-950/30">
        <div class="grid gap-0 lg:grid-cols-[1fr_360px]">
            <section class="flex min-h-[72vh] flex-col">
                <header class="border-b border-white/10 bg-gradient-to-r from-cyan-500/15 via-blue-500/10 to-fuchsia-500/10 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-cyan-300">GroundZero</p>
                    <h1 class="mt-3 text-3xl font-black tracking-tight text-white">Chat-first business command panel</h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-300">
                        TitanZero powers the conversation. TitanCore handles AI orchestration. Titan Pro remains the full table/admin system.
                    </p>
                </header>

                <div class="flex-1 space-y-4 overflow-y-auto p-5">
                    @foreach ($messages as $message)
                        <div @class([
                            'max-w-4xl rounded-2xl p-4 shadow-lg',
                            'ml-auto bg-cyan-500 text-slate-950' => ($message['role'] ?? null) === 'user',
                            'mr-auto border border-white/10 bg-white/8 text-slate-100' => ($message['role'] ?? null) !== 'user',
                        ])>
                            <div class="text-xs font-bold uppercase tracking-wider opacity-70">
                                {{ $message['title'] ?? ucfirst($message['role'] ?? 'message') }}
                            </div>
                            <div class="mt-2 whitespace-pre-line text-sm leading-6">{{ $message['body'] ?? '' }}</div>

                            @if (! empty($message['cards']))
                                <div class="mt-4 grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
                                    @foreach ($message['cards'] as $card)
                                        <div class="rounded-xl border border-white/10 bg-slate-900/70 p-3">
                                            <div class="text-[11px] uppercase tracking-wide text-slate-400">{{ $card['label'] ?? 'Status' }}</div>
                                            <div class="mt-1 text-sm font-bold text-white">{{ $card['value'] ?? '' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <form wire:submit="send" class="border-t border-white/10 bg-slate-900/95 p-4">
                    <div class="flex gap-3">
                        <textarea
                            wire:model="command"
                            rows="2"
                            class="min-h-[64px] flex-1 resize-none rounded-2xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white outline-none ring-cyan-400/40 placeholder:text-slate-500 focus:ring"
                            placeholder="Ask GroundZero: show overdue jobs, summarize today, draft customer follow-ups, inspect invoices..."
                        ></textarea>
                        <button type="submit" class="rounded-2xl bg-cyan-400 px-6 py-3 text-sm font-black text-slate-950 shadow-lg shadow-cyan-950/40 transition hover:bg-cyan-300">
                            Run
                        </button>
                    </div>
                </form>
            </section>

            <aside class="border-l border-white/10 bg-slate-900/70 p-5">
                <h2 class="text-sm font-black uppercase tracking-[0.25em] text-cyan-300">Live Context</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($timeline as $event)
                        <div class="rounded-2xl border border-white/10 bg-slate-950/70 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-bold text-white">{{ $event['title'] ?? 'GroundZero event' }}</div>
                                <div class="text-xs text-cyan-300">{{ $event['time'] ?? 'Live' }}</div>
                            </div>
                            <p class="mt-2 text-sm text-slate-400">{{ $event['description'] ?? '' }}</p>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-white/10 bg-slate-950/70 p-4 text-sm text-slate-400">
                            GroundZero is ready. Live business context will appear here.
                        </div>
                    @endforelse
                </div>

                <h2 class="mt-8 text-sm font-black uppercase tracking-[0.25em] text-cyan-300">Quick Commands</h2>
                <div class="mt-4 grid gap-2">
                    @foreach ([
                        'Summarize today’s jobs',
                        'Show overdue invoices',
                        'Which cleaners need attention?',
                        'Draft customer follow-ups',
                        'Find operational risks this week',
                    ] as $prompt)
                        <button
                            type="button"
                            wire:click="$set('command', @js($prompt))"
                            class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-left text-sm text-slate-300 hover:border-cyan-400/50 hover:text-white"
                        >{{ $prompt }}</button>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</x-filament-panels::page>
