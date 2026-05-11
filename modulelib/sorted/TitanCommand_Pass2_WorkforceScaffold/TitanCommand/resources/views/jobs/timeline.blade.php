@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <div>
      <div class="muted">Job #{{ $job->id }}</div>
      <div style="font-size:16px;font-weight:700">{{ $job->title }}</div>
    </div>
    <div>
      <a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$job->id.'?html=1') }}">Back</a>
    </div>
  </div>
</div>

<div class="card">
  <h3 style="margin:0 0 10px">Timeline</h3>
  <table>
    <tr><th>Type</th><th>At</th><th>Meta</th></tr>
    @foreach($events as $e)
      <tr>
        <td>{{ $e->event_type }}</td>
        <td class="muted">{{ $e->created_at }}</td>
        <td class="muted">{{ $e->meta_json ? json_encode($e->meta_json) : '' }}</td>
      </tr>
    @endforeach
  </table>
</div>
@endsection
