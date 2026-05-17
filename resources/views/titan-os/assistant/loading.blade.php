{{--
  Titan Zero Loading Indicator

  Displays a simple animated loading indicator while the assistant is
  generating a response.  This placeholder uses a CSS animation; a more
  sophisticated spinner can be added later.
--}}
<div class="titan-zero-loading flex items-center space-x-2 p-2">
    <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg>
    <span>Thinking…</span>
</div>