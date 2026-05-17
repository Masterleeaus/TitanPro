<?php

namespace App\Support\TitanOS;

/**
 * A simple data transfer object to define a Titan OS app.  Applications can
 * register themselves in the titan_os_apps config file by providing an array
 * matching these properties.  The registry will hydrate instances of this
 * class for type‑safe access.
 */
class AppDefinition
{
    public string $key = '';
    public string $name = '';
    public string $label = '';
    public string $panel = '';
    public string $route = '';
    public string $icon = '';
    public string $category = '';
    public bool $enabled = true;
    public bool $locked = false;
    public bool $upgrade_required = false;
    public bool $coming_soon = false;
    public bool $beta = false;
    public bool $hidden = false;
    public bool $disabled = false;

    public function __construct(array $definition = [])
    {
        foreach ($definition as $prop => $value) {
            if (property_exists($this, $prop)) {
                $this->$prop = $value;
            }
        }
    }
}