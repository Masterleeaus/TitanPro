{{--
  Titan Zero Assistant Panel

  Vue 3 mount point for BusinessOsChatPanel.vue.  The component is bootstrapped
  by resources/js/titan-os-panel.ts via the @vite directive below.
--}}
<div
    id="titan-zero-panel-root"
    class="flex flex-col h-full"
    data-title="Business OS"
></div>

@vite(['resources/js/titan-os-panel.ts'])