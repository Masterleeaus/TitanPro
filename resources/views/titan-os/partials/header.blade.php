{{--
  Titan OS Header

  The header bar for the Business OS shell.  It shows a simple brand
  placeholder and could later include navigation breadcrumbs or user
  information.  Keep markup minimal to avoid conflicting with Filament's
  native header.
--}}
<header class="titan-os-header py-2 px-4 flex items-center justify-between border-b">
    <div class="flex items-center space-x-2">
        <span class="font-semibold">Titan OS</span>
    </div>
    <button
        data-titan-os-launcher-toggle
        class="titan-os-launcher-button">
        <!-- Icon placeholder -->
        <span class="sr-only">Open Launcher</span>
        ☰
    </button>
</header>