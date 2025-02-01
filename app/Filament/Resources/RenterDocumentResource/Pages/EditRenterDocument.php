<?php

namespace App\Filament\Resources\RenterDocumentResource\Pages;

use App\Filament\Resources\RenterDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRenterDocument extends EditRecord
{
    protected static string $resource = RenterDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
