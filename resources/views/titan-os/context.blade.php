{{--
  Titan OS Context Injection

  This view should be included in pages that require Business OS context.  It
  serializes the resolved OsContext into a global JavaScript variable so
  front‑end scripts can read panel, route and user metadata.  Only generic
  fields are exposed.
--}}
@php
    /** @var \App\Support\TitanOS\Context\OsContext $context */
    $context = $context ?? app(\App\Support\TitanOS\Context\OsContextResolver::class)::resolve();
@endphp

<script>
    window.titanOsContext = @json($context);
</script>