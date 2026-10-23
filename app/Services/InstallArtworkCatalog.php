<?php

namespace App\Services;

use App\Models\Product;
use App\Support\ContentBlocks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstallArtworkCatalog
{
    public function install(bool $dryRun = false): array
    {
        $products = $this->products();

        return DB::transaction(function () use ($products, $dryRun) {
            $result = ['created' => 0, 'skipped' => 0, 'paintings' => 0, 'sculptures' => 0];
            foreach ($products as $entry) {
                $paths = array_column($entry['images'], 'path');
                // Also recognize renamed and archived pieces. Never resurrect or overwrite them.
                $exists = Product::withTrashed()->where(function ($query) use ($entry, $paths) {
                    $query->where('slug', $entry['slug'])->orWhere(function ($query) use ($entry, $paths) {
                        $query->where('kind', $entry['kind'])->whereHas('images', fn ($images) => $images->whereIn('path', $paths));
                    });
                })->exists();
                if ($exists) {
                    $result['skipped']++;

                    continue;
                }
                $result['created']++;
                $result[$entry['kind'] === 'painting' ? 'paintings' : 'sculptures']++;
                if (! $dryRun) {
                    $images = $entry['images'];
                    unset($entry['images']);
                    $product = Product::create($entry);
                    $product->images()->createMany($images);
                }
            }

            return $result;
        });
    }

    private function products(): array
    {
        $path = database_path('catalog/artworks.json');
        if (! is_readable($path)) {
            throw new \RuntimeException('No se encuentra database/catalog/artworks.json. Actualiza el repositorio completo.');
        }
        try {
            $catalog = json_decode(file_get_contents($path), true, 64, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new \RuntimeException('El catálogo versionado no contiene JSON válido.');
        }
        if (! is_array($catalog)) {
            throw new \RuntimeException('El catálogo debe contener un objeto con versión y productos.');
        }
        $fields = [...(new Product)->getFillable(), 'images'];
        Validator::make($catalog, [
            'version' => 'required|integer|in:1',
            'products' => 'required|array|min:1|max:500',
            'products.*' => 'required|array:'.implode(',', $fields),
            'products.*.title' => 'required|string|max:180',
            'products.*.slug' => 'required|string|max:180|distinct:strict|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'products.*.kind' => ['required', Rule::in(['painting', 'sculpture'])],
            'products.*.category' => 'nullable|string|max:80',
            'products.*.summary' => 'nullable|string|max:1500',
            'products.*.technique' => 'nullable|string|max:255',
            'products.*.dimensions' => 'nullable|string|max:255',
            'products.*.edition' => 'nullable|string|max:255',
            'products.*.year' => 'nullable|integer|between:1900,2100',
            'products.*.price_minor' => 'nullable|integer|min:0|max:9999999999',
            'products.*.currency' => 'required|string|size:3|regex:/^[A-Z]{3}$/',
            'products.*.stock' => 'required|integer|between:0,10000',
            'products.*.availability' => ['required', Rule::in(['available', 'inquiry', 'reserved', 'sold'])],
            'products.*.is_published' => 'required|boolean',
            'products.*.is_featured' => 'required|boolean',
            'products.*.images' => 'required|array|min:1|max:12',
            'products.*.images.*' => 'required|array:path,alt,position',
            'products.*.images.*.path' => ['required', 'string', 'distinct:strict', 'regex:~^/images/(?:art|sculptures)/[a-z0-9-]+\.webp$~'],
            'products.*.images.*.alt' => 'required|string|max:255',
            'products.*.images.*.position' => 'required|integer|between:0,65535',
        ])->validate();
        foreach ($catalog['products'] as $entry) {
            $blocks = Validator::make($entry, ContentBlocks::rules());
            $blocks->after(ContentBlocks::validate(...));
            $blocks->validate();
            foreach ($entry['images'] as $image) {
                $file = public_path($image['path']);
                if (! is_readable($file) || (@getimagesize($file)[2] ?? null) !== IMAGETYPE_WEBP) {
                    throw new \RuntimeException('Falta una imagen válida del catálogo: '.$image['path'].'. No se ha importado ninguna obra.');
                }
            }
        }

        return $catalog['products'];
    }
}
