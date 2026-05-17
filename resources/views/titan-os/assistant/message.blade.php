{{--
  Titan Zero Message Component

  Renders a single chat message.  This file is unused in this pass but
  reserved for future componentisation.
--}}
<div class="titan-zero-message {{ $author ?? 'user' }}">
    {{ $slot ?? '' }}
</div>