namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\reports;


@extends('layouts.app')
@section('content')
<div class="jobmanager-dark" style="padding:24px;">
  <h2 style="color:#fff;">AI Audit Logs (Last 50)</h2>
  <div class="card" style="overflow:auto;max-height:80vh;">
    <pre style="color:#a7b0be;">@foreach($lines as $line){{ $line }}&#10;@endforeach</pre>
  </div>
</div>
@endsection
