<?php

namespace App\Filament\Resources\Denominations\Pages;

use App\Filament\Resources\Denominations\DenominationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDenomination extends EditRecord
{
    protected static string $resource = DenominationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
