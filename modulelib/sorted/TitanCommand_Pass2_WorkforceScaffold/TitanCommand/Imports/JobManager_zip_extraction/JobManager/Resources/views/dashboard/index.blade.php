
namespace Modules\JobManager\Resources\views\dashboard;


@extends('layouts.app')
@section('content')
<style>
.jobmanager-dark { background: #0f1216; min-height: 100vh; }
.jobmanager-container { max-width: 1400px; margin: 0 auto; padding: 24px; }
.job-h1 { font-size: 1.6rem; font-weight: 700; color: #fff; letter-spacing: .3px; }
.job-sub { color: #a7b0be; font-size: .95rem; }
.grid { display: grid; grid-gap: 16px; }
.grid-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
.grid-4 { grid-template-columns: repeat(4, minmax(0,1fr)); }
.card { background: #161b22; border: 1px solid #232a35; border-radius: 10px; padding: 16px; color: #e4e8ef; }
.card h4 { color: #fff; margin: 0 0 8px; font-size: .95rem; font-weight: 600; }
.kpi { display:flex; align-items:center; justify-content:space-between; }
.kpi .value { font-size: 1.8rem; font-weight: 800; color: #ffffff; }
.kpi .badge { font-size:.75rem; padding:4px 8px; border-radius:999px; background:#0b2b3a; color:#7ed3ff; border:1px dashed #245e7a;}
.accent-orange { color: #ff8b00; }
.accent-blue { color: #29a0ff; }
.hr-muted { border:0; border-top:1px solid #232a35; margin:12px 0; }
.list { list-style:none; padding:0; margin:0; }
.list li { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #232a35; }
.list li:last-child { border-bottom:0; }
.ai-panel { border: 1px dashed #3a4454; background: #0f141b; padding: 14px; border-radius: 10px; }
.ai-actions { display:flex; gap:8px; flex-wrap:wrap; }
.ai-actions button { background:#1b2330; color:#e4e8ef; border:1px solid #2a3647; padding:8px 10px; border-radius:8px; cursor:pointer; }
.ai-actions button:hover { border-color:#3b4a61; }
@media (max-width: 1024px) { .grid-4 { grid-template-columns: repeat(2, minmax(0,1fr)); } }
@media (max-width: 720px) { .grid-2, .grid-4 { grid-template-columns: 1fr; } }
</style>

<div class="jobmanager-dark">
  <div class="jobmanager-container">
    @includeIf('jobmanager::partials.ai_panel')

    <div class="card" style="margin-top:12px;">
      <div class="kpi">
        <div>
          <div class="job-h1">Job Manager</div>
          <div class="job-sub">Dark dashboard overview for field service & jobs</div>
        </div>
        <div class="accent-orange">🛠️</div>
      </div>
    </div>

    <div class="grid grid-4" style="margin-top:16px;">
      <div class="card">
        <h4>Open Jobs <span class="accent-orange">•</span></h4>
        <div class="kpi"><div class="value">{{ $openJobs }}</div><div class="badge">Live</div></div>
      </div>
      <div class="card">
        <h4>Today’s Appointments <span class="accent-blue">•</span></h4>
        <div class="kpi"><div class="value">{{ $todayAppointments }}</div><div class="badge">Today</div></div>
      </div>
      <div class="card">
        <h4>Pending Compliance</h4>
        <div class="kpi"><div class="value">{{ $pendingCompliance }}</div><div class="badge">Action</div></div>
      </div>
      <div class="card">
        <h4>Monthly Revenue</h4>
        <div class="kpi"><div class="value">${{ number_format($monthlyRevenue, 2) }}</div><div class="badge">MTD</div></div>
      </div>
    </div>

    <div class="grid grid-2" style="margin-top:16px;">
      @includeIf('jobmanager::widgets.upcoming-jobs')
      @includeIf('jobmanager::widgets.recent-activity')
    </div>
  </div>
</div>
@endsection
