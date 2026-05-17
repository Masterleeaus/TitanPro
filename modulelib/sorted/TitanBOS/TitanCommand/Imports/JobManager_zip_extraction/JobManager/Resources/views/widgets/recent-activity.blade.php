
namespace Modules\JobManager\Resources\views\widgets;


<div class="card">
  <h4>Recent Job Activity</h4>
  <ul class="list">
    @forelse($recentActivity as $job)
      <li>
        <span>#{{ $job->id }} — {{ \Illuminate\Support\Str::limit($job->title ?? 'Job', 40) }}</span>
        <span>{{ optional($job->updated_at)->diffForHumans() }}</span>
      </li>
    @empty
      <li>No recent activity.</li>
    @endforelse
  </ul>
</div>
