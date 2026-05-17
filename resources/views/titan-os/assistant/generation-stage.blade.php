{{--
  Generation Stage Indicator

  Displays the current stage of UI generation when Titan Zero is
  constructing a dynamic UI.  In this pass, only generic messages are
  displayed.
--}}
<div class="titan-zero-generation-stage p-2 text-xs text-gray-500">
    {{ $stage ?? 'Preparing response…' }}
</div>