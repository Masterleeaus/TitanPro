
namespace Modules\JobManager\Resources\views\workorders;


<x-app-layout><h1 class='text-2xl font-bold'>Create Job</h1><form method='POST' action='{{ route("jobmanager.orders.store") }}'>@csrf<input name='client_id' placeholder='Client ID' class='border p-2'><button class='btn'>Save</button></form></x-app-layout>