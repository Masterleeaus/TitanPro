@php
    use App\Models\PlatformSetting;
    use App\Services\Accessibility\AccessibilityAudit;
    use Illuminate\Support\Facades\Schema;

    $accessibilitySettings = $accessibilitySettings ?? (Schema::hasTable('platform_settings') ? PlatformSetting::current() : null);
@endphp

@if ($accessibilitySettings)
    <style id="titan-accessibility-theme">
        {!! app(AccessibilityAudit::class)->themeStyles($accessibilitySettings) !!}
    </style>
@endif
