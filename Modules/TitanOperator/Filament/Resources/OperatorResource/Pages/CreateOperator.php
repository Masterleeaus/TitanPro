<?php

namespace Modules\TitanOperator\Filament\Resources\OperatorResource\Pages;

use Illuminate\Support\Str;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateOperator extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\OperatorResource::class;

        protected function mutateFormDataBeforeCreate(array $data): array
        {
            $data['uuid'] ??= (string) Str::uuid();
            $data['user_id'] ??= auth()->id();

            return $data;
        }
    }
} else {
    class CreateOperator {}
}
