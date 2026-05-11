<?php

namespace Modules\TitanOperator\Filament\Resources\ChannelResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateChannel extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\ChannelResource::class;

        protected function mutateFormDataBeforeCreate(array $data): array
        {
            $userId = auth()->id();

            if ($userId === null) {
                throw new \RuntimeException('Authenticated user required to create operator channels.');
            }

            $data['user_id'] ??= $userId;

            return $data;
        }
    }
} else {
    class CreateChannel {}
}
