{{--
  Workspace Index

  Displays a grid of all available apps so the user can navigate to their
  workspace.  This page mirrors the app switcher but is accessible via
  /os/apps.
--}}
@extends('layouts.titan-os')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Workspaces</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach (\App\Support\TitanOS\AppRegistry::all() as $app)
            <a href="{{ url('/os/workspace/' . $app->key) }}" class="p-4 border rounded hover:bg-gray-100 dark:hover:bg-gray-800">
                <div class="text-lg">{{ $app->icon }}</div>
                <div class="font-medium">{{ $app->name }}</div>
                <div class="text-sm text-gray-500">{{ $app->label }}</div>
            </a>
        @endforeach
    </div>
@endsection