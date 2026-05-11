
<x-card>
<div>
<h3>Titan Trust Command</h3>

<div class="tabs flex gap-2 mb-4">
<button onclick="showTab('overview')">Overview</button>
<button onclick="showTab('inspections')">Inspections</button>
<button onclick="showTab('incidents')">Incidents</button>
<button onclick="showTab('evidence')">Evidence</button>
</div>

<div id="tab-overview">@include('dashboard.partials.trust-overview')</div>
<div id="tab-inspections" style="display:none">@include('dashboard.partials.trust-inspections')</div>
<div id="tab-incidents" style="display:none">@include('dashboard.partials.trust-incidents')</div>
<div id="tab-evidence" style="display:none">@include('dashboard.partials.trust-evidence')</div>

<script>
function showTab(tab){
document.querySelectorAll('[id^=tab-]').forEach(e=>e.style.display='none');
document.getElementById('tab-'+tab).style.display='block';
}
</script>

</div>
</x-card>
