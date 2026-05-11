@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <div class="muted">Job #{{ $job->id }}</div>
  <div style="font-size:18px;font-weight:800;margin-top:4px">{{ $job->title }}</div>
  <div class="row" style="margin-top:10px">
    <span class="pill">{{ $job->status }}</span>
    @if($job->scheduled_start)
      <span class="pill">Scheduled: {{ $job->scheduled_start }}{{ $job->scheduled_end ? ' - '.$job->scheduled_end : '' }}</span>
    @endif
  </div>
</div>

<div class="row">
  <a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$job->id.'/timeline?html=1') }}">Timeline</a>
  <a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$job->id.'/checklists?html=1') }}">Checklists</a>
  <a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$job->id.'/evidence?html=1') }}">Evidence</a>
  <a class="btn primary" href="{{ url('/dashboard/user/command/jobs/'.$job->id.'/proof-pack?html=1') }}">Proof Pack</a>
</div>
@endsection
