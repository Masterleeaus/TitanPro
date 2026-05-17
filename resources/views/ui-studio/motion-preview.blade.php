<!DOCTYPE html>
<html lang="en" id="motion-root" data-motion-preset="{{ $uiTokens['tokens']['--motion-preset'] }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI Studio — Motion Preview</title>
    <style id="motion-generated-css">{!! $uiTokens['generated_css'] !!}</style>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; background: #f8fafc; }
        .preview-section { background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgb(0 0 0 / 8%); }
        .controls { display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; margin-bottom: 1.5rem; }
        .control-group { display: flex; flex-direction: column; gap: 0.25rem; }
        label { font-size: 0.8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        select { padding: 0.5rem 0.75rem; border-radius: 0.375rem; border: 1px solid #e2e8f0; font-size: 0.9rem; background: white; }
        .motion-card { width: 200px; height: 120px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; cursor: pointer; }
        .motion-sidebar { width: 60px; height: 160px; background: #1e293b; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; writing-mode: vertical-rl; font-size: 0.75rem; }
        .motion-overlay { width: 200px; height: 120px; background: rgb(15 23 42 / 60%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .motion-skeleton { width: 200px; height: 20px; background: #e2e8f0; border-radius: 0.25rem; }
        .motion-press { padding: 0.75rem 1.5rem; background: #6366f1; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; }
        .motion-target { width: 200px; height: 100px; background: linear-gradient(135deg, #f59e0b, #ef4444); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .preview-grid { display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: flex-start; }
        .preview-item { display: flex; flex-direction: column; gap: 0.5rem; align-items: center; }
        .preview-label { font-size: 0.75rem; color: #64748b; }
        .token-display { font-size: 0.75rem; font-family: monospace; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.25rem; color: #475569; }
    </style>
</head>
<body>
    <h1>UI Studio — Motion Preview</h1>
    <p>This page is used for end-to-end browser validation of the UI Studio motion layer.</p>

    <div class="preview-section">
        <h2>Motion Controls</h2>
        <form id="motion-form" class="controls">
            <div class="control-group">
                <label for="preset-select">Motion Preset</label>
                <select id="preset-select" name="motion_preset" data-token="--motion-preset">
                    @foreach($uiTokens['preset_options'] as $value => $label)
                        <option value="{{ $value }}"{{ $value === $uiTokens['tokens']['--motion-preset'] ? ' selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="control-group">
                <label for="speed-select">Motion Speed</label>
                <select id="speed-select" name="motion_speed" data-token="--motion-speed">
                    @foreach($uiTokens['speed_options'] as $value => $label)
                        <option value="{{ $value }}"{{ $value === $uiTokens['tokens']['--motion-speed'] ? ' selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="control-group">
                <label for="easing-select">Motion Easing</label>
                <select id="easing-select" name="motion_ease" data-token="--motion-ease">
                    @foreach($uiTokens['easing_options'] as $value => $label)
                        <option value="{{ $value }}"{{ $value === $uiTokens['tokens']['--motion-ease'] ? ' selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div id="token-values" style="margin-bottom: 1rem;">
            <span class="token-display" id="token-preset">--motion-preset: <span id="val-preset">{{ $uiTokens['tokens']['--motion-preset'] }}</span></span>
            &nbsp;
            <span class="token-display" id="token-speed">--motion-speed: <span id="val-speed">{{ $uiTokens['tokens']['--motion-speed'] }}</span></span>
            &nbsp;
            <span class="token-display" id="token-ease">--motion-ease: <span id="val-ease">{{ $uiTokens['tokens']['--motion-ease'] }}</span></span>
        </div>
    </div>

    <div class="preview-section">
        <h2>Live Preview</h2>
        <div class="preview-grid">
            <div class="preview-item">
                <div class="motion-target" id="preview-target">Fade / Slide</div>
                <span class="preview-label">motion-target</span>
            </div>
            <div class="preview-item">
                <div class="motion-sidebar" id="preview-sidebar">Sidebar</div>
                <span class="preview-label">motion-sidebar</span>
            </div>
            <div class="preview-item">
                <div class="motion-card" id="preview-card">Hover Card</div>
                <span class="preview-label">motion-card</span>
            </div>
            <div class="preview-item">
                <div class="motion-overlay" id="preview-overlay">Blur Overlay</div>
                <span class="preview-label">motion-overlay</span>
            </div>
            <div class="preview-item">
                <div class="motion-skeleton" id="preview-skeleton"></div>
                <span class="preview-label">motion-skeleton (shimmer)</span>
            </div>
            <div class="preview-item">
                <button class="motion-press" id="preview-press">Press Me</button>
                <span class="preview-label">motion-press</span>
            </div>
        </div>
    </div>

    <div class="preview-section" id="reduced-motion-info">
        <h2>Accessibility</h2>
        <p>
            The generated CSS includes a
            <code>@media (prefers-reduced-motion: reduce)</code>
            override that disables all animation and transition effects.
        </p>
        <div id="reduced-motion-status" class="token-display">
            @media (prefers-reduced-motion: reduce) override: <strong>active when system preference is set</strong>
        </div>
    </div>

    {{-- Inline CSS variable values for DOM inspection by browser tests --}}
    <meta id="motion-meta"
          data-preset="{{ $uiTokens['tokens']['--motion-preset'] }}"
          data-speed="{{ $uiTokens['tokens']['--motion-speed'] }}"
          data-ease="{{ $uiTokens['tokens']['--motion-ease'] }}"
          data-presets="{{ implode(',', $uiTokens['presets']) }}"
          data-live-preview="{{ $motionTab['live_preview'] ? 'true' : 'false' }}"
          data-generated-css-length="{{ strlen($uiTokens['generated_css']) }}">

    <script>
        // Update CSS custom properties and data attribute on root when controls change.
        document.querySelectorAll('[data-token]').forEach(function (select) {
            select.addEventListener('change', function () {
                var token = this.getAttribute('data-token');
                var value = this.value;

                // Update CSS variable on :root
                document.documentElement.style.setProperty(token, value);

                // Mirror into data-motion-preset for preset changes
                if (token === '--motion-preset') {
                    document.documentElement.setAttribute('data-motion-preset', value);
                    document.getElementById('val-preset').textContent = value;
                } else if (token === '--motion-speed') {
                    document.getElementById('val-speed').textContent = value;
                } else if (token === '--motion-ease') {
                    document.getElementById('val-ease').textContent = value;
                }
            });
        });
    </script>
</body>
</html>
