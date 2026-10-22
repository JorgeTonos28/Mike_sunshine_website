<?php

namespace App\Actions\Catalog;

use App\Models\Product;
use App\Models\StudioSetting;
use App\Services\ImageUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaveProduct
{
    public function handle(Product $product, array $data, array $uploads = []): Product
    {
        return DB::transaction(function () use ($product, $data, $uploads) {
            $remove = $data['remove_images'] ?? [];
            $existing = $product->exists ? $product->images()->whereNotIn('id', $remove)->count() : 0;
            if ($data['is_published'] && ! $existing && ! count($uploads)) {
                throw ValidationException::withMessages(['uploads' => 'Añade una imagen antes de publicar la obra.']);
            }
            if ($existing + count($uploads) > 12) {
                throw ValidationException::withMessages(['uploads' => 'Una obra admite un máximo de 12 imágenes.']);
            }
            $price = $data['price'] ?? null;
            $priceMinor = $price === null || $price === '' ? null : ((int) explode('.', $price)[0] * 100 + (int) str_pad(explode('.', $price)[1] ?? '', 2, '0'));
            unset($data['price'], $data['uploads'], $data['remove_images']);
            $product->fill([...$data, 'price_minor' => $priceMinor, 'currency' => StudioSetting::current()->currency])->save();
            $product->images()->whereIn('id', $remove)->delete();
            foreach ($uploads as $index => $upload) {
                $product->images()->create(['path' => app(ImageUpload::class)->store($upload), 'alt' => $product->title, 'position' => $existing + $index]);
            }

            return $product;
        });
    }
}
