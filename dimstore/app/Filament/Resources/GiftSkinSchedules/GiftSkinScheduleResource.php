<?php

namespace App\Filament\Resources\GiftSkinSchedules;

use App\Filament\Resources\GiftSkinSchedules\Pages\CreateGiftSkinSchedule;
use App\Filament\Resources\GiftSkinSchedules\Pages\EditGiftSkinSchedule;
use App\Filament\Resources\GiftSkinSchedules\Pages\ListGiftSkinSchedules;
use App\Filament\Resources\GiftSkinSchedules\Schemas\GiftSkinScheduleForm;
use App\Filament\Resources\GiftSkinSchedules\Tables\GiftSkinSchedulesTable;
use App\Models\GiftSkinSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GiftSkinScheduleResource extends Resource
{
    protected static ?string $model = GiftSkinSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // ✅ FIX UTAMA: HARUS UnitEnum|string|null (bukan BackedEnum)
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Jadwal Gift Skin';
    protected static ?string $modelLabel = 'Jadwal Gift Skin';
    protected static ?string $pluralModelLabel = 'Jadwal Gift Skin';

    // Jangan pakai 'name' kalau kolomnya tidak ada
    protected static ?string $recordTitleAttribute = 'nickname';

    public static function form(Schema $schema): Schema
    {
        return GiftSkinScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GiftSkinSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGiftSkinSchedules::route('/'),
            'create' => CreateGiftSkinSchedule::route('/create'),
            'edit'   => EditGiftSkinSchedule::route('/{record}/edit'),
        ];
    }
}
