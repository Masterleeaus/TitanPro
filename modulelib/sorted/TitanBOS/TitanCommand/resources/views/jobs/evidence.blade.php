@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <div>
      <div class="muted">Job #{{ $job->id }}</div>
      <div style="font-size:16px;font-weight:700">{{ $job->title }}</div>
    </div>
    <div>
      <a class="btn primary" href="{{ url('/dashboard/user/command/jobs/'.$job->id.'/proof-pack?html=1') }}">Proof Pack</a>
    </div>
  </div>
</div>

<div class="card">
  <h3 style="margin:0 0 10px">Evidence</h3>
  <table>
    <tr><th>Type</th><th>Label</th><th>Ref</th><th>Meta</th></tr>
    @foreach($evidence as $ev)
      <tr>
        <td>{{ $ev->evidence_type }}</td>
        <td>{{ $ev->label }}</td>
        <td class="muted">{{ $ev->ref }}</td>
        <td class="muted">{{ $ev->meta_json ? json_encode($ev->meta_json) : '' }}</td>
      </tr>
    @endforeach
  </table>
</div>
@endsection
