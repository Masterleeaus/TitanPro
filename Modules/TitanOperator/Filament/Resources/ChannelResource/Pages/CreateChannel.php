<?php

namespace Modules\TitanOperator\Filament\Resources\ChannelResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateChannel extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\ChannelResource::class;
    }
} else {
    class CreateChannel {}
}
