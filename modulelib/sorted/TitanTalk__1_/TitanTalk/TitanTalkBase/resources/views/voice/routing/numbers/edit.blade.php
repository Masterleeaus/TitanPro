@extends('marketing-bot::voice.layouts.master')
@section('content')

<h4 class="mb-3">Edit Inbound Number</h4>
@include('marketing-bot::voice.partials.flash')

<form method="POST" action="{{ route('dashboard.user.marketing-bot.voice.routing.numbers.update', $number->id) }}" class="card card-body">
  @csrf
  @include('marketing-bot::voice.routing/numbers/form', ['number' => $number])
  <div class="mt-3">
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('dashboard.user.marketing-bot.voice.routing.numbers.index') }}" class="btn btn-light">Back</a>
  </div>
</form>

@endsection
