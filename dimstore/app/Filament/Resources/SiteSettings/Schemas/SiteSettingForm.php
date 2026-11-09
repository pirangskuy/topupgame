<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('site_name')
                ->label('Nama Website')
                ->required()
                ->default('Dimstore'),

            FileUpload::make('logo')
                ->label('Logo Website')
                ->image()
                ->disk('public')
                ->directory('site')
                ->visibility('public')
                ->imageEditor()
                ->imageCropAspectRatio('1:1')
                ->helperText('Upload logo (PNG transparan disarankan).'),

            TextInput::make('whatsapp')
                ->label('WhatsApp')
                ->placeholder('contoh: 089691106601 atau +6289691106601'),

            TextInput::make('instagram')
                ->label('Instagram')
                ->placeholder('contoh: dimstore.id'),

            Textarea::make('footer_text')
                ->label('Teks Footer')
                ->columnSpanFull()
                ->rows(4),
        ]);
    }
}
