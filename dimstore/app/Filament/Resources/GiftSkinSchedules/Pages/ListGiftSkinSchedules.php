<?php

namespace App\Filament\Resources\GiftSkinSchedules\Pages;

use App\Filament\Resources\GiftSkinSchedules\GiftSkinScheduleResource;
use App\Models\GiftSkinSchedule;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab; // ✅ INI YANG BENAR
use Illuminate\Database\Eloquent\Builder;

class ListGiftSkinSchedules extends ListRecords
{
    protected static string $resource = GiftSkinScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Status')
                ->badge(fn () => GiftSkinSchedule::query()->count()),

            'done' => Tab::make('Done')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'done'))
                ->badge(fn () => GiftSkinSchedule::query()->where('status', 'done')->count())
                ->badgeColor('success'),

            'progress' => Tab::make('Progress')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'progress'))
                ->badge(fn () => GiftSkinSchedule::query()->where('status', 'progress')->count())
                ->badgeColor('warning'),
        ];
    }
}
