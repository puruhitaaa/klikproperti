<?php

namespace App\Filament\Resources\PropertyDocumentResource\Pages;

use App\Filament\Resources\PropertyDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyDocuments extends ListRecords
{
    protected static string $resource = PropertyDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
