Real table integration pass v1.9.2

Deep scan findings from current DB:
- tz_jobs exists
- tz_invoices exists
- tz_bookings was not found in the scanned database dump

Integration decisions:
- booking mode falls back to tz_jobs with status=booked when no native booking table exists
- invoice mode creates draft tz_invoices rows when auto_send_invoice is enabled
- execution logs are written to ext_quotemaker_execution_logs

What now happens:
- saving a builder returns both execution_preview and execution_result
- if enabled, quote/booking builders can create tz_jobs rows
- if enabled, invoice builders can create tz_invoices rows
