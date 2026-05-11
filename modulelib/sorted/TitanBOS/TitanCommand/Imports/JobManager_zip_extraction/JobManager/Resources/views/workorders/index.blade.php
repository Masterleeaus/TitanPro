
namespace Modules\JobManager\Resources\views\workorders;


<x-app-layout><h1 class='text-2xl font-bold'>Job Manager</h1><ul>@foreach($orders as $o)<li><a href='{{ route("jobmanager.orders.show", $o->id) }}'>WO #{{ $o->id }}</a></li>@endforeach</ul>{{ $orders->links() }}</x-app-layout>