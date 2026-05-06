<?php

namespace Modules\TitanChatbot\Services;

class ModuleAgentBindingService
{
    public function actionMapPath(): string
    {
        return dirname(__DIR__) . '/AI/Actions/action-map.json';
    }

    /** @return array<string, mixed> */
    public function actionMap(): array
    {
        $path = $this->actionMapPath();
        if (! is_file($path)) {
            return ['tools' => []];
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        return is_array($decoded) ? $decoded : ['tools' => []];
    }

    /** @return array<int, array<string, mixed>> */
    public function tools(): array
    {
        $map = $this->actionMap();
        return array_values((array) ($map['tools'] ?? []));
    }

    public function resolveAction(string $tool): ?string
    {
        foreach ($this->tools() as $definition) {
            if (($definition['name'] ?? null) === $tool) {
                $action = $definition['action'] ?? null;
                return is_string($action) && class_exists($action) ? $action : null;
            }
        }

        return null;
    }
}
