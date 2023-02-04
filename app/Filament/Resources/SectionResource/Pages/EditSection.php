<?php

namespace App\Filament\Resources\SectionResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SectionResource;

class EditSection extends EditRecord
{
    protected static string $resource = SectionResource::class;

    // redirects
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
