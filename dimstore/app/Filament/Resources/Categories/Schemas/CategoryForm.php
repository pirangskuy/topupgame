<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug((string) $state));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->helperText('Otomatis dari nama, boleh diedit kalau mau.'),

                TextInput::make('sort_order')
                    ->label('Urutan (paling kecil = paling kiri & jadi default pertama)')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
