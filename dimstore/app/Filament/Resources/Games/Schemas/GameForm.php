<?php

namespace App\Filament\Resources\Games\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;

class GameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Game')
                ->schema([
                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('slug', Str::slug((string) $state));
                        }),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Toggle::make('requires_server')
                        ->label('Requires server?')
                        ->default(false),

                    FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->disk('public')          // ✅ WAJIB: simpan ke storage/app/public
                        ->directory('games')      // ✅ folder: storage/app/public/games
                        ->visibility('public')    // ✅ WAJIB: bisa diakses dari /storage/...
                        ->imageEditor()
                        ->imagePreviewHeight('220')
                        ->maxSize(4096)
                        ->nullable(),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}
