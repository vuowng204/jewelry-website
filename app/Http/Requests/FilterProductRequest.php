<?php

namespace App\Http\Requests;

use App\Services\ProductService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FilterProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'array'],
            'price.*' => ['string'],
            'material' => ['nullable', 'array'],
            'material.*' => ['string', 'max:255'],
            'q' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'string'],
        ];
    }

    /**
     * Return normalized filter values for ProductService::list().
     *
     * @return array{
     *     category: string,
     *     price: list<string>,
     *     material: list<string>,
     *     q: string,
     *     sort: string
     * }
     */
    public function filters(): array
    {
        $validated = $this->validated();

        $selectedPrices = array_values(array_intersect(
            (array) ($validated['price'] ?? []),
            array_keys(ProductService::PRICE_RANGES)
        ));

        $selectedMaterials = array_values(array_filter(
            array_map(
                static fn (mixed $value): string => trim((string) $value),
                (array) ($validated['material'] ?? [])
            ),
            static fn (string $value): bool => $value !== ''
        ));

        $sort = (string) ($validated['sort'] ?? 'default');

        if (! array_key_exists($sort, ProductService::SORT_OPTIONS)) {
            $sort = 'default';
        }

        return [
            'category' => trim((string) ($validated['category'] ?? '')),
            'price' => $selectedPrices,
            'material' => $selectedMaterials,
            'q' => trim((string) ($validated['q'] ?? '')),
            'sort' => $sort,
        ];
    }
}
