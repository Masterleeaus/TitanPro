<?php
/**
 * TitanChatbot test bootstrap – standalone autoloader (no full Laravel app required).
 */

// ── PSR-4 autoloader for this module ────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $defaultBaseDir = __DIR__ . '/../';
    $modulesBaseDir = dirname(__DIR__, 2) . '/';

    $prefixMap = [
        'Modules\\TitanEchoAssist\\' => $defaultBaseDir,
        'Modules\\TitanChatbot\\' => $defaultBaseDir,
        'Modules\\TitanCore\\' => $modulesBaseDir . 'TitanCore/',
    ];

    foreach ($prefixMap as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }
        $relative = substr($class, strlen($prefix));
        $file     = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
        // File not found for this prefix; try next prefix
    }
});

// ── Global helper stubs ───────────────────────────────────────────────────────
if (!function_exists('app')) {
    function app(string $class = null)
    {
        if ($class === null) { return null; }
        if (class_exists($class)) { return new $class(); }
        return null;
    }
}
if (!function_exists('config')) {
    class TitanChatbotConfigStub {
        private static array $values = [];
        public static function set(array $values): void { static::$values = array_merge(static::$values, $values); }
        public static function reset(): void { static::$values = []; }
        public static function get(string $key = null, mixed $default = null): mixed
        {
            if ($key === null) {
                return static::$values;
            }

            if (array_key_exists($key, static::$values)) {
                return static::$values[$key];
            }

            $value = static::$values;
            foreach (explode('.', $key) as $segment) {
                if (!is_array($value) || !array_key_exists($segment, $value)) {
                    return $default;
                }
                $value = $value[$segment];
            }

            return $value;
        }
    }

    function config(string $key = null, $default = null) { return TitanChatbotConfigStub::get($key, $default); }
}
if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $val = $_ENV[$key] ?? getenv($key);
        return ($val !== false && $val !== null) ? $val : $default;
    }
}
if (!function_exists('now')) {
    function now()
    {
        return new class {
            public function toIso8601String(): string { return date('c'); }
            public function toDateString(): string    { return date('Y-m-d'); }
            public function __toString(): string      { return date('c'); }
        };
    }
}
if (!function_exists('report')) {
    function report(\Throwable $e): void {}
}
if (!function_exists('data_get')) {
    function data_get(mixed $target, string|array|null $key, mixed $default = null): mixed
    {
        if ($key === null) {
            return $target;
        }

        $segments = is_array($key) ? $key : explode('.', $key);
        foreach ($segments as $segment) {
            if (is_array($target) && array_key_exists($segment, $target)) {
                $target = $target[$segment];
                continue;
            }

            return $default;
        }

        return $target;
    }
}
if (!function_exists('data_set')) {
    function data_set(array &$target, string|array|null $key, mixed $value): array
    {
        if ($key === null) {
            $target = $value;
            return $target;
        }

        $segments = is_array($key) ? $key : explode('.', $key);
        $current = &$target;
        foreach ($segments as $segment) {
            if (!isset($current[$segment]) || !is_array($current[$segment])) {
                $current[$segment] = [];
            }
            $current = &$current[$segment];
        }
        $current = $value;

        return $target;
    }
}

// ── Minimal Illuminate facade stubs ──────────────────────────────────────────
// Cache
if (!class_exists('Illuminate\\Support\\Facades\\Cache')) {
    class TitanChatbotCacheStub {
        private static array $store = [];
        public static function get(string $key, mixed $default = null): mixed { return static::$store[$key] ?? $default; }
        public static function put(string $key, mixed $value, int $ttl = 3600): void { static::$store[$key] = $value; }
        public static function increment(string $key, int $by = 1): int { static::$store[$key] = (int)(static::$store[$key] ?? 0) + $by; return static::$store[$key]; }
        public static function forget(string $key): void { unset(static::$store[$key]); }
        public static function flush(): void { static::$store = []; }
    }
    class_alias('TitanChatbotCacheStub', 'Illuminate\\Support\\Facades\\Cache');
}
// Log
if (!class_exists('Illuminate\\Support\\Facades\\Log')) {
    class TitanChatbotLogStub {
        public static function info(string $m, array $c = []): void {}
        public static function warning(string $m, array $c = []): void {}
        public static function error(string $m, array $c = []): void {}
        public static function debug(string $m, array $c = []): void {}
        public static function channel(string $n): static { return new static(); }
    }
    class_alias('TitanChatbotLogStub', 'Illuminate\\Support\\Facades\\Log');
}
// Http
if (!class_exists('Illuminate\\Support\\Facades\\Http')) {
    class TitanChatbotHttpStub {
        /** @var array|null Preset response payload for the next request. */
        private static ?array $mockResponse = null;
        private static bool   $mockFailed   = false;
        /** @var array<string, array{response:array, failed:bool}> */
        private static array $mockByUrl = [];
        private static ?array $lastRequest = null;
        private array $headers = [];

        public static function fake(array $response = [], bool $failed = false): void
        {
            static::$mockResponse = $response;
            static::$mockFailed   = $failed;
            static::$mockByUrl    = [];
        }

        public static function fakeForUrl(string $url, array $response = [], bool $failed = false): void
        {
            static::$mockByUrl[$url] = ['response' => $response, 'failed' => $failed];
        }

        public static function resetFake(): void
        {
            static::$mockResponse = null;
            static::$mockFailed   = false;
            static::$mockByUrl    = [];
            static::$lastRequest  = null;
        }

        public static function withToken(string $t): static
        {
            $instance = new static();
            $instance->headers['Authorization'] = 'Bearer ' . $t;

            return $instance;
        }

        public static function withHeaders(array $h): static
        {
            $instance = new static();
            $instance->headers = $h;

            return $instance;
        }

        public static function withQueryParameters(array $p): static { return new static(); }

        public static function lastRequest(): ?array
        {
            return static::$lastRequest;
        }

        public function post(string $u, array $d = []): object {
            $mock = static::$mockByUrl[$u] ?? null;
            $failed   = $mock['failed'] ?? static::$mockFailed;
            $response = $mock['response'] ?? static::$mockResponse;
            static::$lastRequest = ['url' => $u, 'body' => $d, 'headers' => $this->headers];
            return new class($failed, $response) {
                public function __construct(private bool $failed, private ?array $resp) {}
                public function failed(): bool { return $this->failed; }
                public function status(): int  { return $this->failed ? 503 : 200; }
                public function json(string $k = null, mixed $def = null): mixed {
                    if ($this->resp === null) { return $def; }
                    if ($k === null) { return $this->resp; }
                    // Support dot-notation key access
                    $data = $this->resp;
                    foreach (explode('.', $k) as $segment) {
                        if (!is_array($data) || !array_key_exists($segment, $data)) { return $def; }
                        $data = $data[$segment];
                    }
                    return $data;
                }
            };
        }
    }
    class_alias('TitanChatbotHttpStub', 'Illuminate\\Support\\Facades\\Http');
}
// DB
if (!class_exists('Illuminate\\Support\\Facades\\DB')) {
    class TitanChatbotDbStub {
        public static function select(string $s, array $b = []): array { return []; }
        public static function table(string $t): static { return new static(); }
        public function insert(array $d): bool { return true; }
    }
    class_alias('TitanChatbotDbStub', 'Illuminate\\Support\\Facades\\DB');
}
// Schema
if (!class_exists('Illuminate\\Support\\Facades\\Schema')) {
    class TitanChatbotSchemaStub {
        public static function hasTable(string $t): bool { return false; }
    }
    class_alias('TitanChatbotSchemaStub', 'Illuminate\\Support\\Facades\\Schema');
}
// Event
if (!class_exists('Illuminate\\Support\\Facades\\Event')) {
    class TitanChatbotEventStub {
        public static function dispatch(object $e): void {}
    }
    class_alias('TitanChatbotEventStub', 'Illuminate\\Support\\Facades\\Event');
}

// ── Illuminate\Database\Eloquent\Model stub ───────────────────────────────────
if (!class_exists('Illuminate\\Database\\Eloquent\\Model')) {
    class TitanChatbotEloquentModel {
        protected array $attributes = [];
        public function __construct(array $attrs = []) { $this->attributes = $attrs; }
        public function getAttribute(string $k): mixed { return $this->attributes[$k] ?? null; }
        public static function find(mixed $id): ?static { return null; }
        public static function firstOrCreate(array $s, array $a = []): static { return new static(array_merge($s,$a)); }
        public function fill(array $a): static { $this->attributes = array_merge($this->attributes, $a); return $this; }
        public function save(): bool { return true; }
        public function load(string ...$r): static { return $this; }
    }
    class_alias('TitanChatbotEloquentModel', 'Illuminate\\Database\\Eloquent\\Model');
}

// ── Illuminate\Database\Eloquent\Scope stub ───────────────────────────────────
if (!interface_exists('Illuminate\\Database\\Eloquent\\Scope')) {
    interface TitanChatbotEloquentScope {}
    class_alias('TitanChatbotEloquentScope', 'Illuminate\\Database\\Eloquent\\Scope');
}

// ── Illuminate\Routing\Controller stub ───────────────────────────────────────
if (!class_exists('Illuminate\\Routing\\Controller')) {
    class TitanChatbotControllerStub {}
    class_alias('TitanChatbotControllerStub', 'Illuminate\\Routing\\Controller');
}

// ── Illuminate\Foundation\Support\Providers\EventServiceProvider stub ─────────
if (!class_exists('Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider')) {
    class TitanChatbotESPStub {
        public function __construct() {}
        public function register(): void {}
        public function boot(): void {}
    }
    class_alias('TitanChatbotESPStub', 'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider');
}

// ── Illuminate\Support\ServiceProvider stub ────────────────────────────────────
if (!class_exists('Illuminate\\Support\\ServiceProvider')) {
    class TitanChatbotSPStub {
        public function __construct($app = null) {}
        public function register(): void {}
        public function boot(): void {}
        protected function mergeConfigFrom(string $p, string $k): void {}
        protected function loadMigrationsFrom(string $p): void {}
        protected function loadViewsFrom(string $p, string $n): void {}
        protected function loadTranslationsFrom(string $p, string $n): void {}
        protected function loadRoutesFrom(string $p): void {}
    }
    class_alias('TitanChatbotSPStub', 'Illuminate\\Support\\ServiceProvider');
}
