<?php

namespace Modules\EInvoice\UI\ControlPanel;

interface ControlPanelPageContract
{
    public function getModuleAlias(): string;

    public function getControlPanelSections(): array;
}
