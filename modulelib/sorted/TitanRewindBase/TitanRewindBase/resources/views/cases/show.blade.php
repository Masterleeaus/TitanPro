@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="mb-1">Case #{{ $case->id }} — {{ $case->title }}</h3>
      <div class="text-muted">
        Severity: <strong>{{ $case->severity }}</strong> · Status: <strong>{{ $case->status }}</strong>
      </div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('titanrewind.cases.index') }}" class="btn btn-outline-secondary">Back</a>
      @if($case->status !== 'resolved')
        <form method="POST" action="{{ route('titanrewind.cases.resolve', ['case' => $case->id]) }}">
          @csrf
          <button class="btn btn-success" type="submit">Resolve</button>
        </form>
      @endif
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header"><strong>Propose a Fix</strong></div>
        <div class="card-body">
          <form method="POST" action="{{ route('titanrewind.cases.proposeFix', ['case' => $case->id]) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Fix type</label>
              <input name="fix_type" class="form-control" value="metadata_update" required>
              <div class="form-text">Template includes a safe example: <code>metadata_update</code> (allowlisted).</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Proposal JSON</label>
              <textarea name="proposal_json" class="form-control" rows="6">{ "target_table":"titan_rewind_cases", "target_id": {{ $case->id }}, "meta_key":"note", "meta_value":"example correction" }</textarea>
              <div class="form-text">This is the payload used by the fix handler.</div>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="1" id="requires_confirmation" name="requires_confirmation" checked>
              <label class="form-check-label" for="requires_confirmation">Requires confirmation</label>
            </div>
            <button class="btn btn-primary" type="submit">Propose Fix</button>
          </form>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header"><strong>Fixes</strong></div>
        <div class="card-body">
          @forelse($fixes as $f)
            <div class="border rounded p-2 mb-2">
              <div class="d-flex justify-content-between">
                <div>
                  <div><strong>#{{ $f->id }}</strong> · <code>{{ $f->fix_type }}</code></div>
                  <div class="text-muted small">Status: {{ $f->status }} @if($f->requires_confirmation) · confirm_token: <code>{{ $f->confirm_token }}</code>@endif</div>
                </div>
                <div class="text-end">
                  <form method="POST" action="{{ route('titanrewind.cases.applyFix', ['case' => $case->id]) }}">
                    @csrf
                    <input type="hidden" name="fix_id" value="{{ $f->id }}">
                    @if($f->status === 'proposed')
                      <input type="hidden" name="confirm" value="1">
                      <button class="btn btn-sm btn-warning" type="submit">Confirm</button>
                    @elseif($f->status === 'confirmed')
                      <button class="btn btn-sm btn-success" type="submit">Apply</button>
                    @else
                      <button class="btn btn-sm btn-outline-secondary" type="button" disabled>Done</button>
                    @endif
                  </form>
                </div>
              </div>
              <details class="mt-2">
                <summary class="small text-muted">proposal</summary>
                <pre class="small mb-0">{{ json_encode($f->proposal_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) }}</pre>
              </details>
              @if($f->error_text)
                <div class="text-danger small mt-2">{{ $f->error_text }}</div>
              @endif
            </div>
          @empty
            <div class="text-muted">No fixes yet.</div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card">
        <div class="card-header"><strong>Audit Events</strong></div>
        <div class="card-body">
          @forelse($events as $e)
            <div class="border rounded p-2 mb-2">
              <div class="d-flex justify-content-between">
                <div>
                  <div><strong>{{ $e->event_type }}</strong></div>
                  <div class="text-muted small">
                    {{ \Carbon\Carbon::parse($e->created_at)->format('Y-m-d H:i') }}
                    · actor: {{ $e->actor_type }}{{ $e->actor_id ? '#'.$e->actor_id : '' }}
                    · entity: {{ $e->entity_type }}{{ $e->entity_id ? '#'.$e->entity_id : '' }}
                  </div>
                </div>
                <div class="text-muted small text-end">
                  <div>hash: <code>{{ substr($e->event_hash, 0, 12) }}</code></div>
                  @if($e->prev_event_hash)
                    <div>prev: <code>{{ substr($e->prev_event_hash, 0, 12) }}</code></div>
                  @endif
                </div>
              </div>
              @if(!empty($e->payload_json))
                <details class="mt-2">
                  <summary class="small text-muted">payload</summary>
                  <pre class="small mb-0">{{ json_encode($e->payload_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) }}</pre>
                </details>
              @endif
            </div>
          @empty
            <div class="text-muted">No audit events.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
