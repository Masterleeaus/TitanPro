{{--
  Form Shell Widget

  Presents a generic form container.  Actual fields are passed in via
  $widget['fields'] array.  This pass does not render interactive
  components but structures the shell.
--}}
<form class="titan-os-widget-form p-4 border rounded bg-white dark:bg-gray-900 shadow space-y-2">
    <div class="font-medium mb-2">{{ $widget['title'] ?? 'Form' }}</div>
    @foreach (($widget['fields'] ?? []) as $field)
        <div class="flex flex-col space-y-1">
            <label class="text-sm font-medium">{{ $field['label'] ?? '' }}</label>
            <input type="text" class="border rounded p-1" placeholder="{{ $field['placeholder'] ?? '' }}" />
        </div>
    @endforeach
    <button type="submit" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">{{ $widget['submit_label'] ?? 'Submit' }}</button>
</form>