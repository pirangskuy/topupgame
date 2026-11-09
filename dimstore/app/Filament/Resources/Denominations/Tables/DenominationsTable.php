<?php

namespace App\Filament\Resources\Denominations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DenominationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('game_id', 'asc')
            ->defaultSort('group', 'asc')
            ->defaultSort('sort_order', 'asc')
            ->columns([
                TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('group')
                    ->label('Paket')
                    ->badge()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                // ✅ FILTER GAME (inti kebutuhan kamu)
                SelectFilter::make('game_id')
                    ->label('Game')
                    ->relationship('game', 'name')
                    ->searchable()
                    ->preload(),

                // opsional: filter paket
                SelectFilter::make('group')
                    ->label('Paket')
                    ->options([
                        'paket_a' => 'Paket A',
                        'paket_b' => 'Paket B',
                        'weekly'  => 'Weekly',
                    ]),

                // opsional: filter aktif
                TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
