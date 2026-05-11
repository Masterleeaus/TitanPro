@extends('marketing-bot::voice.layouts.master')
@section('content')

<h4 class="mb-3">Create IVR Menu</h4>
@include('marketing-bot::voice.partials.flash')

<form method="POST" action="{{ route('dashboard.user.marketing-bot.voice.routing.ivr.store') }}" class="card card-body">
  @csrf
  @include('marketing-bot::voice.routing/ivr/form', ['menu' => null])
  <div class="mt-3">
    <button class="btn btn-primary">Create</button>
    <a href="{{ route('dashboard.user.marketing-bot.voice.routing.ivr.index') }}" class="btn btn-light">Cancel</a>
  </div>
</form>

@endsection
