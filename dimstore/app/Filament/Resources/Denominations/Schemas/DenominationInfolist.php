<?php

namespace App\Filament\Resources\Denominations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DenominationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('game.name')->label('Game'),
            TextEntry::make('group')->label('Paket'),
            TextEntry::make('sort_order')->label('Urutan'),
            TextEntry::make('name')->label('Nama'),
            TextEntry::make('price')->label('Harga')->money(currency: 'IDR'),
            IconEntry::make('is_active')->label('Aktif')->boolean(),
            TextEntry::make('created_at')->dateTime()->placeholder('-'),
            TextEntry::make('updated_at')->dateTime()->placeholder('-'),
        ]);
    }
}
