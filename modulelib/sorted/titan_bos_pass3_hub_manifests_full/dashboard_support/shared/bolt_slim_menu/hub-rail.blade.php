
@php
    $hubLinks = $hubLinks ?? [
        ['key' => 'home', 'label' => 'Command Centre', 'link' => url('/dashboard?hub=home'), 'icon' => 'home'],
        ['key' => 'connect', 'label' => 'Connect Core', 'link' => url('/dashboard?hub=connect'), 'icon' => 'users'],
        ['key' => 'service_console', 'label' => 'Service Console', 'link' => url('/dashboard?hub=service_console'), 'icon' => 'calendar'],
        ['key' => 'work', 'label' => 'Work Hub', 'link' => url('/dashboard?hub=work'), 'icon' => 'briefcase'],
        ['key' => 'team', 'label' => 'Team Portal', 'link' => url('/dashboard?hub=team'), 'icon' => 'user-circle'],
        ['key' => 'supply', 'label' => 'Supply Depot', 'link' => url('/dashboard?hub=supply'), 'icon' => 'package'],
        ['key' => 'money', 'label' => 'Money Manager', 'link' => url('/dashboard?hub=money'), 'icon' => 'wallet'],
        ['key' => 'trust_vault', 'label' => 'Trust Vault', 'link' => url('/dashboard?hub=trust_vault'), 'icon' => 'shield-check'],
    ];
@endphp
<div class="titan-hub-rail-wrap hidden xl:block">
    <aside class="titan-hub-rail">
        <div class="titan-hub-rail-head">BOS</div>
        <nav class="titan-hub-rail-nav">
            @foreach ($hubLinks as $hub)
                <a href="{{ $hub['link'] }}" @class(['titan-hub-rail-link','is-active' => ($hub['key'] ?? null) === ($activeHub ?? null)]) title="{{ $hub['label'] }}">
                    <span class="titan-hub-rail-icon">•</span>
                    <span class="titan-hub-rail-text">{{ $hub['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </aside>
</div>
