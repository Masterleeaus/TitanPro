<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\ClientResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CleaningJobs\Filament\Resources\ClientResource;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
}
