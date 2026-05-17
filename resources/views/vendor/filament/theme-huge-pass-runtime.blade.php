{{-- Theme huge-pass runtime layer --}}
<style id="theme-motion-runtime">
    {!! \App\Support\ThemeMotionManager::css() !!}
</style>

@if (class_exists(\App\Support\ThemePresetManager::class))
    <style id="theme-accessibility-runtime">
        {!! \App\Support\ThemeAccessibilityEngine::css(\App\Support\ThemePresetManager::activePreset()) !!}
    </style>
@endif
