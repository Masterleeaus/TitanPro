
<?php
return function(string $hubKey) {
    $base = __DIR__ . '/../../' . $hubKey;
    $read = function(string $file) use ($base) {
        $path = $base . '/' . $file;
        return file_exists($path) ? include $path : [];
    };
    return [
        'manifest' => $read('manifest.php'),
        'kpis' => $read('kpis.php'),
        'actions' => $read('actions.php'),
        'tabs' => $read('tabs.php'),
        'assistants' => $read('assistants.php'),
        'lifecycles' => $read('lifecycles.php'),
    ];
};
