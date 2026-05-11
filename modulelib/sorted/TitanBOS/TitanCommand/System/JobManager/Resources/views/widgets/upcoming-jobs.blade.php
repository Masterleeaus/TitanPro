namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\widgets;


<div class="card">
  <h4>Upcoming Jobs</h4>
  <ul class="list">
    @forelse($upcomingJobs as $job)
      <li>
        <span>#{{ $job->id }} — {{ \Illuminate\Support\Str::limit($job->title ?? 'Job', 40) }}</span>
        <span>{{ optional($job->start_date)->format('d M, H:i') }}</span>
      </li>
    @empty
      <li>No upcoming jobs.</li>
    @endforelse
  </ul>
</div>
