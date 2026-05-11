<?php

namespace Modules\TitanOperator\Filament\Resources\OperatorResource\Pages;

if (class_exists(\Filament\Resources\Pages\EditRecord::class)) {
    class EditOperator extends \Filament\Resources\Pages\EditRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\OperatorResource::class;
    }
} else {
    class EditOperator {}
}
