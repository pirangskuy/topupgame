<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('game_id')
                    ->numeric(),
                TextEntry::make('denomination_id')
                    ->numeric(),
                TextEntry::make('payment_method_id')
                    ->numeric(),
                TextEntry::make('user_id_value'),
                TextEntry::make('server_value')
                    ->placeholder('-'),
                TextEntry::make('whatsapp'),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('status'),
                TextEntry::make('note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
