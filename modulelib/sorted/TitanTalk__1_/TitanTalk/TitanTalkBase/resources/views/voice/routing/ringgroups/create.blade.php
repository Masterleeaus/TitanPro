@extends('marketing-bot::voice.layouts.master')
@section('content')

<h4 class="mb-3">Create Ring Group</h4>
@include('marketing-bot::voice.partials.flash')

<form method="POST" action="{{ route('dashboard.user.marketing-bot.voice.routing.ringgroups.store') }}" class="card card-body">
  @csrf
  @include('marketing-bot::voice.routing/ringgroups/form', ['group' => null])
  <div class="mt-3">
    <button class="btn btn-primary">Create</button>
    <a href="{{ route('dashboard.user.marketing-bot.voice.routing.ringgroups.index') }}" class="btn btn-light">Cancel</a>
  </div>
</form>

@endsection
