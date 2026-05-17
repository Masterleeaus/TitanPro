@extends('titancommand::jobs.layouts.app')

@section('content')
<div class="card">
  <form method="post" action="{{ url('/dashboard/user/command/jobs') }}">
    @csrf
    <div class="field">
      <label class="muted">Title</label>
      <input name="title" placeholder="e.g., Weekly clean — Smith St" required>
    </div>
    <div class="field">
      <label class="muted">Description</label>
      <textarea name="description" rows="4" placeholder="Notes, access, scope..."></textarea>
    </div>
    <div class="grid2">
      <div class="field">
        <label class="muted">Status</label>
        <select name="status">
          <option value="open">open</option>
          <option value="scheduled">scheduled</option>
          <option value="in_progress">in_progress</option>
          <option value="completed">completed</option>
        </select>
      </div>
      <div class="field">
        <label class="muted">Priority</label>
        <select name="priority">
          <option value="normal">normal</option>
          <option value="high">high</option>
          <option value="urgent">urgent</option>
        </select>
      </div>
    </div>
    <button class="btn primary" type="submit">Create Job</button>
  </form>
</div>
@endsection
