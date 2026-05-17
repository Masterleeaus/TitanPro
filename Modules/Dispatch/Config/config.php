<?php

return [
    'name' => 'Dispatch',
    'filament_panel' => env('DISPATCH_FILAMENT_PANEL', 'groundzero'),
    'default_shift_color' => env('DISPATCH_DEFAULT_SHIFT_COLOR', '#2563eb'),
    'timezone' => env('DISPATCH_TIMEZONE', config('app.timezone', 'UTC')),
    'statuses' => [
        'assignment' => ['pending', 'accepted', 'en_route', 'arrived', 'in_progress', 'completed', 'cancelled'],
        'work_order' => ['draft', 'ready_for_dispatch', 'scheduled', 'in_progress', 'completed', 'cancelled'],
        'route' => ['draft', 'planned', 'active', 'completed', 'cancelled'],
    ],
    'models' => [
        'user' => App\Models\User::class,
        'work_order' => Modules\Dispatch\Models\DispatchWorkOrder::class,
        'appointment' => Modules\Dispatch\Models\DispatchAppointment::class,
    ],
];
