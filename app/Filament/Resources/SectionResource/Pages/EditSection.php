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
    // comment this to not showing the delete button
    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
