<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $intro }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2>{{ $intro }}</h2>

    @if(!empty($details))
        <table cellpadding="6" cellspacing="0" border="0" style="border-collapse: collapse;">
            @foreach($details as $label => $value)
                <tr>
                    <td style="font-weight: bold; vertical-align: top;">{{ $label }}</td>
                    <td>{{ is_scalar($value) ? $value : json_encode($value) }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if(!empty($actionUrl))
        <p>
            <a href="{{ $actionUrl }}" style="display:inline-block;padding:10px 14px;background:#2563eb;color:#fff;text-decoration:none;border-radius:4px;">
                {{ $actionLabel ?: __('View booking') }}
            </a>
        </p>
    @endif

    <p>{{ __('Thanks') }},<br>{{ config('app.name') }}</p>
</body>
</html>
