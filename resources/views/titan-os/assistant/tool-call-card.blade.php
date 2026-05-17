{{--
  Tool Call Card

  Displays a summary of a tool invocation in the assistant.  It shows the
  title of the tool and its status.  Real tool execution is not included
  in this pass.
--}}
<div class="titan-zero-tool-call-card p-2 border rounded my-1">
    <div class="text-sm font-medium">{{ $title ?? 'Tool Call' }}</div>
    <div class="text-xs text-gray-500">{{ ucfirst($status ?? 'pending') }}</div>
    @if (!empty($message))
        <div class="text-xs mt-1">{{ $message }}</div>
    @endif
</div>