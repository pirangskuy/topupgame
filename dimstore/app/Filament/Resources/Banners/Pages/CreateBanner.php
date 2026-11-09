<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use App\Models\Banner;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Batasi maksimal 3 banner yang aktif
        if (!empty($data['is_active'])) {
            $activeCount = Banner::where('is_active', true)->count();

            if ($activeCount >= 3) {
                throw ValidationException::withMessages([
                    'is_active' => 'Maksimal 3 banner aktif. Nonaktifkan salah satu banner dulu.',
                ]);
            }
        }

        return $data;
    }
}
