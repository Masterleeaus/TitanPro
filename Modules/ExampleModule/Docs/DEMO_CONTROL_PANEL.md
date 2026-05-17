# Demo Control Panel

This module includes a real assembled demo control panel scaffold.

Important distinction:

- `UI/` is the component library.
- `UI/ControlPanel/DemoControlPanel.php` and `Resources/views/ui/control-panel/demo-control-panel.blade.php` assemble those components into the one-page module panel.

Production modules should rename/copy this to their real module panel and replace `demo.agent` with the real internal module agent. Users still call the assistant **Zero**.
