
<div>
<h4>Inspections</h4>
<table>
<tr><th>ID</th><th>Status</th></tr>
@foreach($inspections ?? [] as $i)
<tr><td>{{ $i->id }}</td><td>{{ $i->status }}</td></tr>
@endforeach
</table>
</div>
