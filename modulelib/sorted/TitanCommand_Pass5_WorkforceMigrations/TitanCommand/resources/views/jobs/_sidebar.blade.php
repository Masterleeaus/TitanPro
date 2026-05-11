<div class="card">
    <div class="card-body">
        <div class="fw-bold mb-2">Jobs Manager</div>
        <div class="list-group">
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.dashboard') }}">Dashboard</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.workorders') }}">Work Orders</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.requests') }}">Requests</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.tasks') }}">Service Tasks</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.parts') }}">Parts & Materials</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.checklists') }}">Checklists</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.inspections') }}">Inspections</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.assets') }}">Assets</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.permits') }}">Permits & Access</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.reports') }}">Reports</a>
            <a class="list-group-item list-group-item-action" href="{{ route('dashboard.user.jobs.settings') }}">Settings</a>
        </div>
        <div class="small text-muted mt-3">
            Pass 1: wired screens + sidebar.<br>
            Pass 2: activate DB + JobManager controllers.
        </div>
    </div>
</div>
