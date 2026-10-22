<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\Product;
use App\Models\StudioSetting;
use App\Services\ImageUpload;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function edit()
    {
        return Inertia::render('admin/Settings', ['settings' => StudioSetting::current()]);
    }

    public function update(SettingsRequest $request, ImageUpload $images)
    {
        $data = $request->safe()->except(['logo', 'favicon', 'hero']);
        if (Product::whereNotNull('price_minor')->where('currency', '!=', $data['currency'])->exists()) {
            throw ValidationException::withMessages(['currency' => 'Hay obras con precio en otra moneda. Quita sus precios antes de cambiar la moneda.']);
        }
        foreach (['logo', 'favicon', 'hero'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field.'_path'] = $images->store($request->file($field));
            }
        }
        StudioSetting::current()->update($data);

        return back()->with('success', 'Información del estudio actualizada.');
    }
}
