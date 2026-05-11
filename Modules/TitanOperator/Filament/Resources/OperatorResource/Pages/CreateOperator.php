<?php

namespace Modules\TitanOperator\Filament\Resources\OperatorResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateOperator extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\OperatorResource::class;
    }
} else {
    class CreateOperator {}
}
