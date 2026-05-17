<?php

namespace Modules\TitanOperator\Filament\Resources\ChannelResource\Pages;

if (class_exists(\Filament\Resources\Pages\ListRecords::class)) {
    class ListChannels extends \Filament\Resources\Pages\ListRecords
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\ChannelResource::class;
    }
} else {
    class ListChannels {}
}
