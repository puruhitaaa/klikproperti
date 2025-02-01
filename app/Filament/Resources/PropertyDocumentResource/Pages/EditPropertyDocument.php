<?php

namespace App\Filament\Resources\PropertyDocumentResource\Pages;

use App\Filament\Resources\PropertyDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyDocument extends EditRecord
{
    protected static string $resource = PropertyDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
