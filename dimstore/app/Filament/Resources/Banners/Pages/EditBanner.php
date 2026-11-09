<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use App\Models\Banner;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['is_active'])) {
            $activeCount = Banner::where('is_active', true)
                ->where('id', '!=', $this->record->id)
                ->count();

            if ($activeCount >= 3) {
                throw ValidationException::withMessages([
                    'is_active' => 'Maksimal 3 banner aktif. Nonaktifkan salah satu banner dulu.',
                ]);
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
