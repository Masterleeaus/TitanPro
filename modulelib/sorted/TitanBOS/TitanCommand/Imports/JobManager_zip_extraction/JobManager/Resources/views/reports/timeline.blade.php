
namespace Modules\JobManager\Resources\views\reports;


@extends('layouts.app')
@section('content')
<div class="jobmanager-dark" style="padding:24px;">
  <h2 style="color:#fff;">Job Timeline</h2>
  @forelse($jobs as $week=>$group)
    <div class="card" style="margin-bottom:12px;">
      <h4>Week {{ $week }}</h4>
      <ul class="list">
        @foreach($group as $job)
          @php
            $color = match($job->status){
              'open' => '#29a0ff',
              'pending' => '#ff8b00',
              'complete' => '#2ecc71',
              default => '#a7b0be'
            };
          @endphp
          <li>
            <span style="color:{{ $color }}">#{{ $job->id }} — {{ $job->title }}</span>
            <span>{{ optional($job->start_date)->format('d M') }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  @empty
    <div class="card"><p>No jobs available.</p></div>
  @endforelse
</div>
@endsection
