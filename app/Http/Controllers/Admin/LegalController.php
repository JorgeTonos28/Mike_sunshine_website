<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use App\Support\ContentBlocks;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LegalController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Legal', ['pages' => LegalPage::all()]);
    }

    public function update(Request $request, LegalPage $page)
    {
        $validator = validator($request->all(), ['title' => 'required|string|max:180', 'is_approved' => 'required|boolean', ...ContentBlocks::rules()]);
        $validator->after(ContentBlocks::validate(...));
        $data = $validator->validate();
        $page->update([...$data, 'version' => $page->version + 1]);

        return back()->with('success', 'Página guardada.');
    }
}
