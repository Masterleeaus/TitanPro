namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\client;


@extends('layouts.app')
@section('content')
<div class="jobmanager-dark" style="padding:24px;">
  <h2 style="color:#fff;">Client Sign-off for Job #{{ $id }}</h2>
  <div class="card">
    <form id="signoff-form" onsubmit="return false;">
      <div style="margin-bottom:8px;">
        <label>Name</label>
        <input type="text" id="client_name" class="form-control" style="width:100%;background:#0f141b;color:#e4e8ef;border:1px solid #2a3647;">
      </div>
      <div style="margin-bottom:8px;">
        <label>Signature (type name)</label>
        <input type="text" id="signature" class="form-control" style="width:100%;background:#0f141b;color:#e4e8ef;border:1px solid #2a3647;">
      </div>
      <div style="margin-bottom:8px;">
        <label>Notes</label>
        <textarea id="notes" class="form-control" style="width:100%;background:#0f141b;color:#e4e8ef;border:1px solid #2a3647;"></textarea>
      </div>
      <button id="submit-signoff" class="badge">Generate Sign-off PDF</button>
    </form>
  </div>
</div>

<script>
document.getElementById('submit-signoff').addEventListener('click', async function() {
  const payload = {
    job_id: {{ $id }},
    client_name: document.getElementById('client_name').value,
    signature: document.getElementById('signature').value,
    notes: document.getElementById('notes').value
  };

  const res = await fetch('{{ route('jobmanager.signoff.pdf') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
    },
    body: JSON.stringify(payload)
  });
  const data = await res.json();
  if(data.file_url){
    window.open(data.file_url, '_blank');
  } else if(data.pdf){
    const bin = atob(data.pdf);
    const arr = new Uint8Array(bin.length);
    for (let i=0;i<bin.length;i++){arr[i]=bin.charCodeAt(i);}
    const url = URL.createObjectURL(new Blob([arr], {type:'application/pdf'}));
    window.open(url, '_blank');
  } else {
    alert('Sign-off generated. Check logs or attachments.');
  }
  console.log('signoff/pdf ->', data);
});
</script>
@endsection
