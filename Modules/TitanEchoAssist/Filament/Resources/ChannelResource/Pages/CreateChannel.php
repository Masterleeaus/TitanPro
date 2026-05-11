<?php

namespace Modules\TitanEchoAssist\Filament\Resources\ChannelResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateChannel extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\ChannelResource::class;
    }
} else {
    class CreateChannel {}
}
