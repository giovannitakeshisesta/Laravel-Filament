<?php

namespace App\Filament\Resources\ClassesResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ClassesResource;

class CreateClasses extends CreateRecord
{
    protected static string $resource = ClassesResource::class;

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
