<?php

namespace App\Filament\Resources\Denominations\Pages;

use App\Filament\Resources\Denominations\DenominationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDenominations extends ListRecords
{
    protected static string $resource = DenominationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
