<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Banner')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->maxLength(100)
                        ->nullable(),

                    FileUpload::make('image')
                        ->label('Gambar Banner')
                        ->disk('public')
                        ->directory('banners')
                        ->image()
                        ->required(),

                    TextInput::make('link_url')
                        ->label('Link (opsional)')
                        ->maxLength(255)
                        ->nullable(),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                ]),
        ]);
    }
}
