<?php

namespace Modules\ZeroPayModule\UI\ControlPanel;

interface ControlPanelPageContract
{
    public function getModuleAlias(): string;

    public function getControlPanelSections(): array;
}
