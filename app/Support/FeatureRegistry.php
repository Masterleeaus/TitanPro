<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class FeatureRegistry
{
    /** @var array<string, mixed> */
    protected array $features = [];

    /** @var array<string, string> */
    protected array $owners = [];

    /** @var string[] */
    protected array $warnings = [];

    public function register(string $key, mixed $value, string $module): void
    {
        if (isset($this->owners[$key]) && $this->owners[$key] !== $module) {
            $warning = "Feature key [{$key}] is already registered by [{$this->owners[$key]}]; duplicate from [{$module}] ignored.";
            $this->warnings[] = $warning;

            Log::warning($warning, [
                'feature' => $key,
                'existing_module' => $this->owners[$key],
                'duplicate_module' => $module,
            ]);

            return;
        }

        $this->features[$key] = $value;
        $this->owners[$key] = $module;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->features);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->features[$key] : $default;
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->features;
    }

    public function owner(string $key): ?string
    {
        return $this->owners[$key] ?? null;
    }

    /** @return string[] */
    public function warnings(): array
    {
        return $this->warnings;
    }
}
