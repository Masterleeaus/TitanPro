@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <h3 style="margin:0 0 10px">Reports</h3>
  <div class="row">
    <a class="btn" href="{{ url('/dashboard/user/command/jobs/reports/performance?html=1') }}">Performance</a>
    <a class="btn" href="{{ url('/dashboard/user/command/jobs/reports/compliance?html=1') }}">Compliance</a>
  </div>
  @if(!empty($reports))
    <div style="margin-top:12px" class="muted">Latest generated</div>
    <table>
      <tr><th>ID</th><th>Type</th><th>Created</th><th>Meta</th></tr>
      @foreach($reports as $r)
        <tr>
          <td>{{ $r->id }}</td>
          <td>{{ $r->report_type }}</td>
          <td class="muted">{{ $r->created_at }}</td>
          <td class="muted">{{ $r->meta_json ? json_encode($r->meta_json) : '' }}</td>
        </tr>
      @endforeach
    </table>
  @endif
</div>
@endsection
