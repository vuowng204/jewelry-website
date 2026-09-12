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
        // Ép mảng về dạng index số nguyên 0, 1, 2...
       $gallery = array_values($this->data['gallery'] ?? []);

        // 1. Lấy danh sách đường dẫn ảnh cũ trong DB
        $oldImages = $this->record->images()->pluck('path', 'id')->toArray();

        // 2. Tìm và xóa file thực tế của những ảnh bị gỡ ra khỏi form
        $removedPaths = array_diff($oldImages, $gallery);
        if (!empty($removedPaths)) {
            foreach ($removedPaths as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        // 3. Xóa các record cũ trong DB và tạo lại theo thứ tự mới
        $this->record->images()->delete();

        foreach ($gallery as $index => $path) {
            $this->record->images()->create([
                'path'       => $path,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
