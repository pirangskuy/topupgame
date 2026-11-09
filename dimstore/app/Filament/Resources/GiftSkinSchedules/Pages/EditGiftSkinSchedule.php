<?php

namespace App\Filament\Resources\GiftSkinSchedules\Pages;

use App\Filament\Resources\GiftSkinSchedules\GiftSkinScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGiftSkinSchedule extends EditRecord
{
    protected static string $resource = GiftSkinScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
