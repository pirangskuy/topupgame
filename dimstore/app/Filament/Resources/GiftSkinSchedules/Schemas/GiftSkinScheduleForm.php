<?php

namespace App\Filament\Resources\GiftSkinSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GiftSkinScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nickname')
                    ->required(),
                TextInput::make('user_id')
                    ->required(),
                DateTimePicker::make('order_date')
                    ->required(),
                DatePicker::make('gift_date'),
                TextInput::make('status')
                    ->required()
                    ->default('progress'),
                TextInput::make('admin_name'),
                TextInput::make('note'),
            ]);
    }
}
