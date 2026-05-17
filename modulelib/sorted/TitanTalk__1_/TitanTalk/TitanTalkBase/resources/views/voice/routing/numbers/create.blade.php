@extends('marketing-bot::voice.layouts.master')
@section('content')

<h4 class="mb-3">Add Inbound Number</h4>
@include('marketing-bot::voice.partials.flash')

<form method="POST" action="{{ route('dashboard.user.marketing-bot.voice.routing.numbers.store') }}" class="card card-body">
  @csrf
  @include('marketing-bot::voice.routing/numbers/form', ['number' => null])
  <div class="mt-3">
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('dashboard.user.marketing-bot.voice.routing.numbers.index') }}" class="btn btn-light">Cancel</a>
  </div>
</form>

@endsection
