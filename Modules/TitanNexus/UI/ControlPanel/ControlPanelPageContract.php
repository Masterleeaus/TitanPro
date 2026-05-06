<?php

namespace Modules\TitanNexus\UI\ControlPanel;

interface ControlPanelPageContract
{
    public function getModuleAlias(): string;

    public function getControlPanelSections(): array;
}
