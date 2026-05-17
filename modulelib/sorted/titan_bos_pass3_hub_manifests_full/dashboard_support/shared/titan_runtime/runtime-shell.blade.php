@props(['hub' => 'work'])
<div x-data="window.TitanRuntime.dashboard('{{ $hub }}')" x-init="init()" class="titan-runtime-shell space-y-4">
    {{ $slot }}
</div>
