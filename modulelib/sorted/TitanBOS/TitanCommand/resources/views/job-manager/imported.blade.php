@extends('layouts.dashboard')

@section('title', $title ?? 'Jobs Manager')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-2">Jobs Manager imported ✅</h3>
                    <p class="mb-0">The full JobManager module source has been extracted into <code>TitanCommand/Imports/JobManager_zip_extraction</code>.</p>
                    <p class="mb-0">Next pass will wire routes, migrations, and UI into this workspace.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
