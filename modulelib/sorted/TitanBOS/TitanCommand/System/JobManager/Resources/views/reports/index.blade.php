namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\reports;


@extends('layouts.app')
@section('content')
<div class="jobmanager-dark" style="padding:24px;">
  <h2 style="color:#fff;">Reports — Overview</h2>
  <div class="grid grid-4" style="display:grid;grid-gap:16px;grid-template-columns:repeat(4,minmax(0,1fr));">
    <div class="card"><h4>MTD Revenue</h4><div class="kpi"><div class="value">${{ number_format($mtdRevenue, 2) }}</div></div></div>
    <div class="card"><h4>Open Jobs</h4><div class="kpi"><div class="value">{{ $openJobs }}</div></div></div>
    <div class="card"><h4>Pending Compliance</h4><div class="kpi"><div class="value">{{ $pendingCompliance }}</div></div></div>
    <div class="card"><h4>Actions</h4>
      <a href="{{ route('jobmanager.reports.revenue') }}" class="badge">Revenue Report</a>
      <a href="{{ route('jobmanager.reports.compliance') }}" class="badge">Compliance Gaps</a>
      <a href="{{ route('jobmanager.reports.timeline') }}" class="badge">Timeline</a>
      <a href="{{ route('jobmanager.reports.audit') }}" class="badge">Audit</a>
    </div>
  </div>
</div>
@endsection
