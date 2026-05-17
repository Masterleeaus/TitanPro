<div class="row g-3 mb-4">
    @foreach(($metrics ?? []) as $metric)
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-uppercase text-muted small">{{ $metric['label'] }}</div>
                    <div class="display-6 fw-bold">{{ $metric['value'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
