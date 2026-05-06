<?php

namespace Modules\BookingModule\UI\ControlPanel;

class ControlPanelLayout
{
    public function sections(): array
    {
        return ['overview', 'bookings', 'schedules', 'dispatch', 'automation', 'health'];
    }
}
