@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="muted">Latest jobs (max 200)</div>
    <div class="row">
      <a class="btn primary" href="{{ url('/dashboard/user/command/jobs/create?html=1') }}">New job</a>
      <a class="btn" href="{{ url('/dashboard/user/command/jobs/dispatch?html=1') }}">Dispatch</a>
    </div>
  </div>
</div>

<div class="card">
  <table>
    <tr><th>ID</th><th>Title</th><th>Status</th><th>Scheduled</th><th></th></tr>
    @foreach($jobs as $j)
      <tr>
        <td><a href="{{ url('/dashboard/user/command/jobs/'.$j->id.'?html=1') }}">{{ $j->id }}</a></td>
        <td>{{ $j->title }}</td>
        <td><span class="pill">{{ $j->status }}</span></td>
        <td class="muted">{{ $j->scheduled_start }} {{ $j->scheduled_end ? ' - '.$j->scheduled_end : '' }}</td>
        <td><a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$j->id.'/timeline?html=1') }}">Timeline</a></td>
      </tr>
    @endforeach
  </table>
</div>
@endsection
