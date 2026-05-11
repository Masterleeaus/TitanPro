
namespace Modules\JobManager\Resources\views\reports;


@extends('layouts.app')
@section('content')
<div class="jobmanager-dark" style="padding:24px;">
  <h2 style="color:#fff;">Compliance Gaps</h2>
  <div class="card">
    <table class="table" style="width:100%;color:#e4e8ef;">
      <thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Updated</th></tr></thead>
      <tbody>
        @forelse($rows as $row)
          <tr>
            <td>#{{ $row->id }}</td>
            <td>{{ $row->title }}</td>
            <td>{{ $row->compliance_status }}</td>
            <td>{{ optional($row->updated_at)->diffForHumans() }}</td>
          </tr>
        @empty
          <tr><td colspan="4">All good — no pending/failed compliance.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
