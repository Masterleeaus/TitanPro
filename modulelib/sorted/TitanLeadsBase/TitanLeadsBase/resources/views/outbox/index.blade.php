@extends('panel.layout.app', ['disable_tblr' => true])

@section('title', 'Outbox')

@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Outbox Drafts</h3>
                    </div>
                    <div class="card-body">
                        <form id="mb-outbox-form" class="row g-2">
                            @csrf
                            <div class="col-md-2">
                                <select class="form-select" name="channel" required>
                                    <option value="sms">SMS</option>
                                    <option value="email">Email</option>
                                    <option value="voice">Voice Call</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" name="to" placeholder="To" required />
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" name="subject" placeholder="Subject (email only)" />
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" name="body" placeholder="Message / Script" required />
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary">Create Draft</button>
                            </div>
                        </form>

                        <hr>

                        <div id="mb-outbox-list" class="small text-muted">Loading…</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function mbFetchDrafts(){
            const res = await fetch("{{ route('dashboard.user.titan-leads.outbox.list') }}");
            const json = await res.json();
            const items = (json.data || []).map(d => {
                const canSend = true;
                return `<div class="border rounded p-2 mb-2">
                    <div><strong>#${d.id}</strong> [${d.channel}] → ${d.to} <span class="text-muted">(${d.status})</span></div>
                    ${d.subject ? `<div><em>${d.subject}</em></div>` : ''}
                    <div>${(d.body||'').toString()}</div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-success" onclick="mbSend(${d.id})">Send Now</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="mbRequestApproval(${d.id})">Request Titan Zero Approval</button>
                    </div>
                </div>`;
            }).join('');
            document.getElementById('mb-outbox-list').innerHTML = items || 'No drafts yet.';
        }

        async function mbSend(id){
            const res = await fetch("{{ route('dashboard.user.titan-leads.outbox.send') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: JSON.stringify({ draft_id: id })
            });
            const json = await res.json();
            alert(json.message || (json.status ? 'Sent' : 'Failed'));
            mbFetchDrafts();
        }

        async function mbRequestApproval(id){
            const res = await fetch("{{ route('dashboard.user.titan-leads.outbox.approval') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: JSON.stringify({ draft_id: id })
            });
            const json = await res.json();
            if (json.approval?.approval_token) {
                alert('Approval requested. Token: ' + json.approval.approval_token);
            } else {
                alert(json.message || 'Unable to request approval');
            }
            mbFetchDrafts();
        }

        document.getElementById('mb-outbox-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const data = Object.fromEntries(new FormData(form).entries());
            const res = await fetch("{{ route('dashboard.user.titan-leads.outbox.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: new FormData(form)
            });
            const json = await res.json();
            if (json.status === 'success') {
                form.reset();
                mbFetchDrafts();
            } else {
                alert(json.message || 'Error');
            }
        });

        mbFetchDrafts();
    </script>
@endsection
