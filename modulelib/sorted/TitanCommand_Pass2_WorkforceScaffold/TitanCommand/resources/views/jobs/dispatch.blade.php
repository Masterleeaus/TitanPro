@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <div class="muted" style="margin-bottom:8px">Jobs needing attention first. (Scheduling + assignment actions are API-backed.)</div>
  <table>
    <tr><th>ID</th><th>Title</th><th>Status</th><th>Scheduled</th><th>Assignees</th><th>Actions</th></tr>
    @foreach($jobs as $j)
      <tr>
        <td><a href="{{ url('/dashboard/user/command/jobs/'.$j['id'].'?html=1') }}">{{ $j['id'] }}</a></td>
        <td>{{ $j['title'] }}</td>
        <td><span class="pill">{{ $j['status'] }}</span></td>
        <td class="muted">{{ $j['scheduled_start'] ?? '' }} {{ !empty($j['scheduled_end']) ? ' - '.$j['scheduled_end'] : '' }}</td>
        <td class="muted">
          @if(!empty($j['assignees']))
            {{ implode(', ', $j['assignees']) }}
          @else
            —
          @endif
        </td>
        <td>
          <a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$j['id'].'/schedule?html=1') }}">Schedule</a>
          <a class="btn" href="{{ url('/dashboard/user/command/jobs/'.$j['id'].'/timeline?html=1') }}">Timeline</a>
        </td>
      </tr>
    @endforeach
  </table>
</div>
@endsection
