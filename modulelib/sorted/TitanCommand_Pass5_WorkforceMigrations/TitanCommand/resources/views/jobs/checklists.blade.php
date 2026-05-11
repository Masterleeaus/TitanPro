@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <div class="muted">Job #{{ $job->id }}</div>
  <div style="font-size:16px;font-weight:700">{{ $job->title }}</div>
</div>

<div class="card">
  <h3 style="margin:0 0 10px">Checklists</h3>
  @foreach($checklists as $c)
    <div style="margin:10px 0 6px">
      <strong>{{ $c->title }}</strong> <span class="pill">{{ $c->status }}</span>
    </div>
    <table style="margin-bottom:14px">
      <tr><th>Item</th><th>Status</th><th>Notes</th></tr>
      @foreach($items->where('checklist_id',$c->id) as $it)
        <tr>
          <td>{{ $it->label }}</td>
          <td class="muted">{{ $it->status }}</td>
          <td class="muted">{{ $it->notes }}</td>
        </tr>
      @endforeach
    </table>
  @endforeach
</div>
@endsection
