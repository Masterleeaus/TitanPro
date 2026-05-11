@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">TitanRewind — Cases</h3>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Severity</th>
            <th>Status</th>
            <th>Detected</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($cases as $c)
            <tr>
              <td>#{{ $c->id }}</td>
              <td>{{ $c->title }}</td>
              <td><span class="badge bg-secondary">{{ $c->severity }}</span></td>
              <td><span class="badge bg-{{ $c->status === 'resolved' ? 'success' : 'warning' }}">{{ $c->status }}</span></td>
              <td>{{ optional($c->detected_at)->format('Y-m-d H:i') }}</td>
              <td class="text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('titanrewind.cases.show', ['case' => $c->id]) }}">Open</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted p-4">No cases yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $cases->links() }}
  </div>
</div>
@endsection
