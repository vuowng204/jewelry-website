<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['gallery'] = $this->record->images()->pluck('path')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        $gallery = $this->data['gallery'] ?? [];

        // Xóa ảnh cũ không còn trong danh sách
        $oldPaths = $this->record->images()->pluck('path')->toArray();
        $removed = array_diff($oldPaths, $gallery);
        foreach ($removed as $path) {
            Storage::disk('public')->delete($path);
        }

        // Xóa record cũ, tạo lại theo gallery mới
        $this->record->images()->delete();
        foreach ($gallery as $index => $path) {
            $this->record->images()->create([
                'path' => $path,
                'sort_order' => $index,
            ]);
        }
    }
}
