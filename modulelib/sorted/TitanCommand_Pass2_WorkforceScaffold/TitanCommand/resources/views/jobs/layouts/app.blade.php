<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Titan Command — Jobs' }}</title>
  <style>
    body{margin:0;font-family:Arial, sans-serif;background:#f7f7f8;color:#111}
    .app{display:flex;min-height:100vh}
    .sidebar{width:260px;background:#0f172a;color:#fff;padding:16px}
    .sidebar h2{margin:0 0 12px;font-size:14px;letter-spacing:.04em;text-transform:uppercase;color:#cbd5e1}
    .nav a{display:block;padding:10px 10px;border-radius:10px;color:#e2e8f0;text-decoration:none;margin-bottom:6px}
    .nav a.active,.nav a:hover{background:#1e293b}
    .content{flex:1;padding:18px}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;margin-bottom:12px}
    .row{display:flex;gap:12px;flex-wrap:wrap}
    .pill{display:inline-block;padding:4px 10px;border-radius:999px;background:#eef2ff;border:1px solid #e0e7ff;font-size:12px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:8px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
    th{font-size:12px;color:#334155;text-transform:uppercase;letter-spacing:.03em}
    .muted{color:#64748b}
    .btn{display:inline-block;padding:8px 10px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;text-decoration:none;color:#111}
    .btn.primary{background:#111827;color:#fff;border-color:#111827}
    input,select,textarea{padding:8px 10px;border:1px solid #e5e7eb;border-radius:10px;width:100%}
    form .field{margin-bottom:10px}
    .grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    @media(max-width:900px){.sidebar{display:none}.content{padding:14px}.grid2{grid-template-columns:1fr}}
  </style>
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <h2>Jobs Manager</h2>
    @include('titancommand::jobs.partials.sidebar')
  </aside>
  <main class="content">
    @if(!empty($heading))
      <div style="margin-bottom:12px">
        <div class="muted" style="font-size:12px">Titan Command</div>
        <h1 style="margin:4px 0 0;font-size:20px">{{ $heading }}</h1>
      </div>
    @endif
    @yield('content')
  </main>
</div>
</body>
</html>
