<?php

namespace Modules\CRMCore\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class DealResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Deal Details')
                ->columns([
                    'default' => 1,
                    'lg' => 1,
                ])
                ->schema([
                    // existing fields stay unchanged
                ]),
        ]);
    }
}
