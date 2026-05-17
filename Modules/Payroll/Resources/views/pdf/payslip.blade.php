<!doctype html>
<html><head><meta charset="utf-8"><title>Payslip</title></head>
<body>
<h1>Payslip</h1>
<p><strong>Employee:</strong> {{ $employee['name'] ?? ('Employee #'.$result['user_id']) }}</p>
<p><strong>Period:</strong> {{ $result['period_from'] ?? '' }} - {{ $result['period_to'] ?? '' }}</p>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<tr><th align="left">Code</th><th align="left">Description</th><th align="right">Amount</th></tr>
@foreach(($result['lines'] ?? []) as $line)
<tr><td>{{ $line['code'] ?? '' }}</td><td>{{ $line['label'] ?? '' }}</td><td align="right">{{ number_format((float)($line['amount'] ?? 0), 2) }}</td></tr>
@endforeach
<tr><td colspan="2" align="right"><strong>Gross</strong></td><td align="right">{{ number_format((float)$result['gross_pay'], 2) }}</td></tr>
<tr><td colspan="2" align="right"><strong>Net</strong></td><td align="right">{{ number_format((float)$result['net_pay'], 2) }}</td></tr>
</table>
</body></html>
