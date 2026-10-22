<?php

namespace App\Http\Requests\Admin;

use App\Support\ContentBlocks;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // FormData cannot encode an empty array; clearing all blocks must still be possible with uploads.
        if (! $this->has('content_blocks')) {
            $this->merge(['content_blocks' => []]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->can('studio') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:180',
            'slug' => ['required', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('products')->ignore($this->route('product'))],
            'kind' => ['required', Rule::in(['painting', 'sculpture'])],
            'category' => 'nullable|string|max:80', 'summary' => 'nullable|string|max:1500',
            'technique' => 'nullable|string|max:255', 'dimensions' => 'nullable|string|max:255',
            'edition' => 'nullable|string|max:255', 'year' => 'nullable|integer|between:1900,2100',
            'price' => ['nullable', 'string', 'regex:/^\d{1,8}(\.\d{1,2})?$/'],
            'stock' => 'required|integer|between:0,10000',
            'availability' => ['required', Rule::in(['available', 'inquiry', 'reserved', 'sold'])],
            'is_published' => 'required|boolean', 'is_featured' => 'required|boolean',
            'uploads' => 'sometimes|array|max:8',
            'uploads.*' => 'required|file|mimetypes:image/jpeg,image/png,image/webp|max:8192|dimensions:max_width=6000,max_height=6000',
            'remove_images' => 'sometimes|array|max:20',
            'remove_images.*' => ['integer', Rule::exists('product_images', 'id')->where('product_id', $this->route('product')?->id ?? 0)],
            'image_order' => 'sometimes|array|max:12',
            'image_order.*' => ['integer', 'distinct', Rule::exists('product_images', 'id')->where('product_id', $this->route('product')?->id ?? 0)],
            'image_alts' => 'sometimes|array|max:12',
            'image_alts.*' => 'array:id,alt',
            'image_alts.*.id' => ['required', 'integer', 'distinct', Rule::exists('product_images', 'id')->where('product_id', $this->route('product')?->id ?? 0)],
            'image_alts.*.alt' => 'required|string|max:255',
            ...ContentBlocks::rules(),
        ];
    }

    public function after(): array
    {
        return [ContentBlocks::validate(...)];
    }
}
