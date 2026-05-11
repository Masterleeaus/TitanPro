@php
  $dir = $call->direction ?? '';
  $st = $call->status ?? '';
  $missed = !empty($call->missed_at);
@endphp

@if($missed)
  <span class="titantalk-badge titantalk-badge--missed">Missed</span>
@elseif($dir==='outbound')
  <span class="titantalk-badge titantalk-badge--outbound">Outbound</span>
@else
  <span class="titantalk-badge titantalk-badge--answered">Inbound</span>
@endif

<span class="ms-2 titantalk-muted">{{ $st }}</span>