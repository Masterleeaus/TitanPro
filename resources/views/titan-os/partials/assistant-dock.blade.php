{{--
  Titan Zero Assistant Dock

  A floating chat dock that can be collapsed or expanded.  It houses the
  assistant panel which includes the message list, input field and
  suggestions.  Only generic suggestions are provided at this stage.  The
  underlying behaviour is implemented in resources/js/titan-zero-assistant.js.
--}}
@php
    $chatbotBubbleConfig = (object) [
        'bubble_design' => 'modern',
        'position' => 'right',
        'trigger_background' => '#2563eb',
        'trigger_foreground' => '#ffffff',
        'avatar' => null,
        'avatar_url' => null,
    ];

    if (class_exists(\Modules\TitanEchoAssist\Models\Chatbot::class)) {
        $chatbotBubbleConfig = \Modules\TitanEchoAssist\Models\Chatbot::query()
            ->where('active', true)
            ->latest('id')
            ->first() ?? $chatbotBubbleConfig;
    }
@endphp

<div
    class="titan-os-assistant fixed bottom-4 {{ ($chatbotBubbleConfig->position?->value ?? $chatbotBubbleConfig->position ?? 'right') === 'left' ? 'left-4' : 'right-4' }} z-40"
    data-titan-zero-dock>
    <!-- Toggle button displayed when the assistant is closed -->
    <button
        data-titan-zero-dock-toggle
        class="titan-os-assistant-button rounded-full p-0 border-0 bg-transparent text-white">
        <x-chatbot.bubble :chatbot="$chatbotBubbleConfig" />
    </button>
    <!-- Assistant panel; hidden by default.  Mode toggled via JS -->
    <div
        class="titan-os-assistant-panel bg-white dark:bg-gray-900 rounded-lg shadow-xl flex flex-col overflow-hidden hidden w-96 h-96"
        data-titan-zero-dock-panel
        data-mode="dock">
        <div class="flex justify-between items-center p-2 border-b">
            <span class="font-semibold">Titan Zero</span>
            <div class="space-x-2">
                <button data-titan-zero-dock-toggle-mode class="text-sm">⧉</button>
                <button data-titan-zero-dock-close class="text-sm">×</button>
            </div>
        </div>
        @include('titan-os.assistant.panel')
    </div>
</div>
