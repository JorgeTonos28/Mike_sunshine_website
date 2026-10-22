<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'slug', 'kind', 'category', 'summary', 'technique', 'dimensions', 'edition', 'year', 'price_minor', 'currency', 'stock', 'availability', 'is_published', 'is_featured', 'content_blocks'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'is_featured' => 'boolean', 'content_blocks' => 'array', 'price_minor' => 'integer', 'stock' => 'integer'];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position')->orderBy('id');
    }

    public function isRequestable(): bool
    {
        return $this->is_published;
    }

    public function availableQuantity(): int
    {
        return in_array($this->availability, ['available', 'inquiry']) ? max(0, $this->stock) : 0;
    }

    public function requiresAvailability(int $quantity): bool
    {
        return $quantity > $this->availableQuantity();
    }

    public function publicData(): array
    {
        return [
            ...$this->only(['id', 'title', 'slug', 'kind', 'category', 'summary', 'technique', 'dimensions', 'edition', 'year', 'price_minor', 'currency', 'availability', 'content_blocks']),
            'requestable' => $this->isRequestable(),
            'available_quantity' => $this->availableQuantity(),
            'images' => $this->images->map(fn (ProductImage $image) => $image->only(['id', 'path', 'alt']))->values(),
        ];
    }
}
