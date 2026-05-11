<nav class="nav">
  @php
    $path = request()->path();
    $base = 'dashboard/user/command/jobs';
    $is = fn($suffix) => str_starts_with($path, $base.$suffix);
  @endphp
  <a href="{{ url('/dashboard/user/command/jobs?html=1') }}" class="{{ $is('') ? 'active' : '' }}">All Jobs</a>
  <a href="{{ url('/dashboard/user/command/jobs/dispatch?html=1') }}" class="{{ $is('/dispatch') ? 'active' : '' }}">Dispatch</a>
  <a href="{{ url('/dashboard/user/command/jobs/create?html=1') }}" class="{{ $is('/create') ? 'active' : '' }}">Create Job</a>
  <a href="{{ url('/dashboard/user/command/jobs/reports?html=1') }}" class="{{ $is('/reports') ? 'active' : '' }}">Reports</a>
  <a href="{{ url('/dashboard/user/command/jobs/settings?html=1') }}" class="{{ $is('/settings') ? 'active' : '' }}">Settings</a>
</nav>
