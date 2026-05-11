
namespace Modules\JobManager\Resources\views\workorders;


<x-app-layout><h1 class='text-2xl font-bold'>Edit Job</h1><form method='POST' action='{{ route("jobmanager.orders.update", $order->id) }}'>@csrf @method('PUT')<input name='status' value='{{ $order->status }}' class='border p-2'><button class='btn'>Update</button></form></x-app-layout>