<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\ProductImage;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function afterCreate(): void
{
    $gallery = array_values($this->data['gallery'] ?? []);
    
    foreach ($gallery as $index => $path) {
        $this->record->images()->create([
            'path' => $path,
            'sort_order' => $index + 1,
        ]);
    }
}
}
