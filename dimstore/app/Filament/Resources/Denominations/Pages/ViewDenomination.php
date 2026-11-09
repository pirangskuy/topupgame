<?php

namespace App\Filament\Resources\Denominations\Pages;

use App\Filament\Resources\Denominations\DenominationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDenomination extends ViewRecord
{
    protected static string $resource = DenominationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
