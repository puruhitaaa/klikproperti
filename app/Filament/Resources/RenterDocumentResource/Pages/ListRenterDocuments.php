<?php

namespace App\Filament\Resources\RenterDocumentResource\Pages;

use App\Filament\Resources\RenterDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRenterDocuments extends ListRecords
{
    protected static string $resource = RenterDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
