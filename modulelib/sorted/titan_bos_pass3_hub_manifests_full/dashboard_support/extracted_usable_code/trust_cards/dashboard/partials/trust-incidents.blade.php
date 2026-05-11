
<div>
<h4>Incidents</h4>
<table>
<tr><th>ID</th><th>Status</th></tr>
@foreach($incidents ?? [] as $i)
<tr><td>{{ $i->id }}</td><td>{{ $i->status }}</td></tr>
@endforeach
</table>
</div>
