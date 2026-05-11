<?php

namespace Modules\TitanOperator\Filament\Resources\OperatorResource\Pages;

if (class_exists(\Filament\Resources\Pages\ListRecords::class)) {
    class ListOperators extends \Filament\Resources\Pages\ListRecords
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\OperatorResource::class;
    }
} else {
    class ListOperators {}
}
