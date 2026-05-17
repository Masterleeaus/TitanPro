{{--
  Titan OS Workspace Frame

  A container element that receives the workspace content for the current
  application.  The `@yield('titan-os-workspace')` directive allows module
  views to inject their own workspace content when loaded at `/os/workspace/{appKey}`.
--}}
<main class="titan-os-workspace p-4">
    @yield('titan-os-workspace')
</main>