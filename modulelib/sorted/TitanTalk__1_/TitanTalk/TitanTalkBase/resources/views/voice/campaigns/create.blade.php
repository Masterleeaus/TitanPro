@extends('marketing-bot::voice.layouts.master')
@section('content')

<h4 class="mb-3">Create Dial Campaign</h4>
@include('marketing-bot::voice.partials.flash')

<form method="POST" action="{{ route('dashboard.user.marketing-bot.voice.campaigns.store') }}" class="card card-body">
  @csrf
  @include('marketing-bot::voice.campaigns/form', ['campaign' => null])
  <div class="mt-3">
    <button class="btn btn-primary">Create</button>
    <a href="{{ route('dashboard.user.marketing-bot.voice.campaigns.index') }}" class="btn btn-light">Cancel</a>
  </div>
</form>

@endsection
