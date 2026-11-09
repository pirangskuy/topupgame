<?php

namespace App\Filament\Resources\Denominations\Schemas;

use App\Models\Denomination;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DenominationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('game_id')
                ->label('Game')
                ->relationship('game', 'name')
                ->searchable()
                ->preload()
                ->required(),

            // ✅ SEKARANG BEBAS: admin bisa ketik nama paket sendiri
            TextInput::make('group')
                ->label('Paket')
                ->required()
                ->default('paket_a')
                ->maxLength(50)
                ->helperText('Bebas isi. Contoh: paket_a, paket_b, weekly, event, membership, dll.')
                // saran otomatis dari data yang sudah pernah dibuat (tetap bisa ngetik manual)
                ->datalist(fn () => Denomination::query()
                    ->select('group')
                    ->distinct()
                    ->orderBy('group')
                    ->pluck('group')
                    ->filter()
                    ->values()
                    ->all()
                ),

            TextInput::make('sort_order')
                ->label('Urutan')
                ->numeric()
                ->default(0)
                ->helperText('Semakin kecil, semakin atas.'),

            TextInput::make('name')
                ->label('Nama Nominal')
                ->required(),

            TextInput::make('price')
                ->label('Harga')
                ->required()
                ->numeric()
                ->prefix('Rp'),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true)
                ->required(),
        ]);
    }
}
