@extends('security::layouts.master')

@section('content')
<div class="container-fluid">
    <h3>Cleaner Security Operations</h3>
    <div class="row mb-3">
        <div class="col">Total: {{ data_get($dashboard, 'cleaners.total', 0) }}</div>
        <div class="col">Pending: {{ data_get($dashboard, 'cleaners.pending', 0) }}</div>
        <div class="col">Active: {{ data_get($dashboard, 'cleaners.active', 0) }}</div>
        <div class="col">On-site: {{ data_get($dashboard, 'cleaners.onsite', 0) }}</div>
        <div class="col">Sites: {{ data_get($dashboard, 'sites.active', 0) }}</div>
    </div>

    <form method="POST" action="{{ route('security.cleaners.store') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col"><input class="form-control" name="name" placeholder="Cleaner name" required></div>
            <div class="col"><input class="form-control" name="phone" placeholder="Phone"></div>
            <div class="col"><input class="form-control" name="vendor_name" placeholder="Vendor"></div>
            <div class="col">
                <select class="form-control" name="site_id">
                    <option value="">Select site</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col"><input class="form-control" name="site_name" placeholder="Or new site"></div>
            <div class="col"><button class="btn btn-primary">Register</button></div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead><tr><th>Code</th><th>Name</th><th>Vendor</th><th>Site</th><th>Status</th><th>On-site</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($cleaners as $cleaner)
            <tr>
                <td>{{ $cleaner->cleaner_code }}</td>
                <td>{{ $cleaner->name }}</td>
                <td>{{ $cleaner->vendor_name }}</td>
                <td>{{ optional($cleaner->site)->name ?? $cleaner->site_name }}</td>
                <td>{{ $cleaner->status }}</td>
                <td>{{ $cleaner->isOnSite() ? 'Yes' : 'No' }}</td>
                <td class="d-flex gap-1">
                    @if($cleaner->status === 'pending')
                        <form method="POST" action="{{ route('security.cleaners.approve', $cleaner) }}">@csrf <button class="btn btn-sm btn-success">Approve</button></form>
                        <form method="POST" action="{{ route('security.cleaners.decision', $cleaner) }}">@csrf <input type="hidden" name="decision" value="reject"><button class="btn btn-sm btn-outline-danger">Reject</button></form>
                    @endif
                    @if($cleaner->status === 'active' && ! $cleaner->isOnSite())
                        <form method="POST" action="{{ route('security.cleaners.check_in', $cleaner) }}">@csrf <button class="btn btn-sm btn-primary">Check in</button></form>
                    @endif
                    @if($cleaner->isOnSite())
                        <form method="POST" action="{{ route('security.cleaners.check_out', $cleaner) }}">@csrf <button class="btn btn-sm btn-warning">Check out</button></form>
                    @endif
                    @if($cleaner->status === 'active')
                        <form method="POST" action="{{ route('security.cleaners.decision', $cleaner) }}">@csrf <input type="hidden" name="decision" value="suspend"><button class="btn btn-sm btn-outline-secondary">Suspend</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $cleaners->links() }}
</div>
@endsection
