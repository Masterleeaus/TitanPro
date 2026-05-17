@props(['chatbot'])

@php
    $design = $chatbot->bubble_design?->value ?? $chatbot->bubble_design ?? 'modern';
    $position = $chatbot->position?->value ?? $chatbot->position ?? 'right';
@endphp

<div class="chatbot-bubble chatbot-bubble--{{ $design }} chatbot-bubble--{{ $position }}"
    style="--trigger-bg: {{ $chatbot->trigger_background ?? '#2563eb' }}; --trigger-fg: {{ $chatbot->trigger_foreground ?? '#ffffff' }}">
    @if(!empty($chatbot->avatar_url))
        <img src="{{ $chatbot->avatar_url }}" alt="Chat" class="chatbot-bubble__avatar">
    @elseif(!empty($chatbot->avatar))
        <img src="{{ $chatbot->avatar }}" alt="Chat" class="chatbot-bubble__avatar">
    @else
        <span class="chatbot-bubble__icon">💬</span>
    @endif
</div>
