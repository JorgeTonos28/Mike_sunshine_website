<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageUpload
{
    public function store(UploadedFile $file): string
    {
        $size = @getimagesize($file->getRealPath());
        if (! $size || $size[0] * $size[1] > 12000000) {
            throw ValidationException::withMessages(['uploads' => 'La imagen admite un máximo de 12 megapíxeles.']);
        }
        $image = @imagecreatefromstring($file->get());
        if (! $image) {
            throw ValidationException::withMessages(['uploads' => 'La imagen no pudo procesarse. Usa PNG, JPG o WebP.']);
        }
        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        ob_start();
        imagewebp($image, null, 88);
        $bytes = ob_get_clean();
        imagedestroy($image);
        $filename = Str::uuid().'.webp';
        Storage::disk('public')->put($filename, $bytes);

        return '/media/'.$filename;
    }
}
