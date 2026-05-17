{{--
  Workspace Show View

  Renders the workspace for a specific app key.  If the module provides its
  own workspace view, it will be rendered via the ProvidesTitanOsWorkspace
  contract.  Otherwise a coming soon placeholder is displayed.
--}}
@extends('layouts.titan-os')

@php
    /** @var \App\Support\TitanOS\AppDefinition $app */
    $app = \App\Support\TitanOS\AppRegistry::get($appKey);
    $workspaceView = null;
    // Attempt to locate a module implementing ProvidesTitanOsWorkspace
    $moduleProviderClass = $app ? (\Str::studly($app->key) . 'Module') : null;
    if (class_exists($moduleProviderClass) && method_exists($moduleProviderClass, 'titanOsWorkspace')) {
        $workspaceView = $moduleProviderClass::titanOsWorkspace();
    }
@endphp

@section('content')
    <h1 class="text-2xl font-semibold mb-4">{{ $app?->name ?? ucfirst($appKey) }} Workspace</h1>
    @if ($workspaceView)
        @include($workspaceView)
    @elseif ($app?->coming_soon)
        @include('titan-os.workspaces.coming-soon', ['app' => $app])
    @elseif ($app?->locked)
        @include('titan-os.workspaces.locked', ['app' => $app])
    @else
        @include('titan-os.workspaces.missing-module', ['app' => $app])
    @endif
@endsection