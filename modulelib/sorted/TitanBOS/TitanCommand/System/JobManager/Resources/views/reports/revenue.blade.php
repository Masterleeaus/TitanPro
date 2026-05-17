namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\reports;


@extends('layouts.app')
@section('content')
<div class="jobmanager-dark" style="padding:24px;">
  <h2 style="color:#fff;">Revenue — {{ $year }}</h2>
  <div class="card">
    <table class="table" style="width:100%;color:#e4e8ef;">
      <thead><tr><th>Month</th><th>Total</th></tr></thead>
      <tbody>
@php $max = $rows->max('total'); @endphp
@foreach($rows as $r)
  @php $pct = $max ? ($r->total / $max) * 100 : 0; @endphp
  <tr>
    <td>{{ \Carbon\Carbon::create()->month($r->m)->format('M') }}</td>
    <td>
      ${{ number_format($r->total,2) }}
      <div style="background:#1b2330;border:1px solid #2a3647;width:100%;height:8px;border-radius:4px;margin-top:2px;">
        <div style="width:{{ $pct }}%;height:100%;background:#29a0ff;border-radius:4px;"></div>
      </div>
    </td>
  </tr>
@endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
