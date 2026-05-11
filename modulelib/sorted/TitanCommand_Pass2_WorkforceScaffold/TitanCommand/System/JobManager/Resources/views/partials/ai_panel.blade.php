namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\partials;


<div class="ai-panel card">
  <h4>AI Copilot <span class="accent-blue">for Jobs</span></h4>
  <p class="job-sub">Ask about schedules, create jobs, generate SWMS, message clients, and more.</p>
  <div class="ai-actions">
    <button data-ai="create_job">+ Create Job</button>
    <button data-ai="offer_slot">Offer Timeslot</button>
    <button data-ai="send_sms">Send SMS</button>
    <button data-ai="send_email">Send Email</button>
    <button data-ai="generate_scr">Generate SWMS</button>
    <button data-ai="check_requirements">Check Requirements</button>
  </div>
  <div id="magicai-embed" style="margin-top:10px;">
    {{-- Put your AICopilot embed/iframe if available --}}
  </div>
</div>

<script>
(function(){
  function postJSON(url, data) {
    return fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(data || {})
    }).then(r => r.json()).catch(e => ({error: e?.message || 'Network error'}));
  }

  const panel = document.querySelector('.ai-panel');
  if(!panel) return;

  panel.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-ai]');
    if(!btn) return;
    const tool = btn.getAttribute('data-ai');

    if(tool === 'create_job') {
      const payload = { title: 'New Job', client_id: 1, start_date: new Date().toISOString().slice(0,10) };
      const res = await postJSON('/api/jobmanager/tool', { tool: 'create_job', payload });
      console.log('create_job ->', res);
      alert(res?.message || 'create_job executed.');
      return;
    }

    if(tool === 'offer_slot') {
      const payload = { job_id: 1, slot: new Date(Date.now()+86400000).toISOString() };
      const res = await postJSON('/api/jobmanager/tool', { tool: 'offer_slot', payload });
      console.log('offer_slot ->', res);
      alert('Offered slot.');
      return;
    }

    if(tool === 'send_sms') {
      const payload = { to: '+61000000000', body: 'Your tradie is on the way.' };
      const res = await postJSON('/api/jobmanager/tool', { tool: 'send_sms', payload });
      console.log('send_sms ->', res);
      alert('SMS sent (check logs).');
      return;
    }

    if(tool === 'send_email') {
      const payload = { to: 'client@example.com', subject: 'Job Update', body: 'Details enclosed.' };
      const res = await postJSON('/api/jobmanager/tool', { tool: 'send_email', payload });
      console.log('send_email ->', res);
      alert('Email sent (check logs).');
      return;
    }

    if(tool === 'generate_scr') {
      const res = await postJSON('/jobmanager/compliance/generate-swms', { job_id: 1, title: 'SWMS – Job 1' });
      console.log('generate_scr ->', res);
      if(res && res.file_url){ window.open(res.file_url, '_blank'); } else { alert('SWMS generation triggered.'); }
      return;
    }

    if(tool === 'check_requirements') {
      const res = await postJSON('/api/jobmanager/tool', { tool: 'check_requirements', payload: { postcode: '3065', trade: 'plumber' } });
      console.log('check_requirements ->', res);
      alert('Requirements check requested.');
      return;
    }
  });
})();
</script>
