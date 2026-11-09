<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use App\Models\Banner;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        $activeCount = Banner::where('is_active', true)->count();

        return [
            CreateAction::make()
                ->label('Tambah Banner')
                // kalau mau tetap bisa create, kasih hint via tooltip:
                ->tooltip($activeCount >= 3
                    ? 'Maks 3 banner aktif. Nonaktifkan salah satu jika ingin mengaktifkan banner baru.'
                    : null),

                // kalau mau “keras” (tombol disable):
                // ->disabled($activeCount >= 3)
                // ->tooltip('Maks 3 banner aktif. Nonaktifkan salah satu dulu.'),
        ];
    }
}
