<?php

namespace Modules\TitanEchoAssist\Filament\Resources\ChannelResource\Pages;

if (class_exists(\Filament\Resources\Pages\EditRecord::class)) {
    class EditChannel extends \Filament\Resources\Pages\EditRecord
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\ChannelResource::class;
    }
} else {
    class EditChannel {}
}
