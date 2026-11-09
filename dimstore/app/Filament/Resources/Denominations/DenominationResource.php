<?php

namespace App\Filament\Resources\Denominations;

use App\Filament\Resources\Denominations\Pages\CreateDenomination;
use App\Filament\Resources\Denominations\Pages\EditDenomination;
use App\Filament\Resources\Denominations\Pages\ListDenominations;
use App\Filament\Resources\Denominations\Pages\ViewDenomination;
use App\Filament\Resources\Denominations\Schemas\DenominationForm;
use App\Filament\Resources\Denominations\Schemas\DenominationInfolist;
use App\Filament\Resources\Denominations\Tables\DenominationsTable;
use App\Models\Denomination;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DenominationResource extends Resource
{
    protected static ?string $model = Denomination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DenominationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DenominationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DenominationsTable::configure($table);
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
            'index' => ListDenominations::route('/'),
            'create' => CreateDenomination::route('/create'),
            'view' => ViewDenomination::route('/{record}'),
            'edit' => EditDenomination::route('/{record}/edit'),
        ];
    }
}
