<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><strong>Titan Zero Navigation</strong></div>
    <div class="list-group list-group-flush">
        @foreach(($zeroNavigation ?? []) as $item)
            <a href="{{ route($item['route']) }}" class="list-group-item list-group-item-action {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <div class="fw-semibold">{{ $item['label'] }}</div>
                <div class="small {{ request()->routeIs($item['route']) ? 'text-white-50' : 'text-muted' }}">{{ $item['description'] }}</div>
            </a>
        @endforeach
    </div>
</div>
