<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('game_id')
                    ->required()
                    ->numeric(),
                TextInput::make('denomination_id')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_method_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id_value')
                    ->required(),
                TextInput::make('server_value'),
                TextInput::make('whatsapp')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
}
