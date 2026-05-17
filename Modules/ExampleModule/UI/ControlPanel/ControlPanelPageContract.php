<?php

namespace Modules\ExampleModule\UI\ControlPanel;

interface ControlPanelPageContract
{
    public function getModuleAlias(): string;

    public function getControlPanelSections(): array;
}
