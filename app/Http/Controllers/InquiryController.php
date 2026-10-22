<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyStudio;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:160', 'email' => 'required|email|max:255', 'subject' => 'required|in:painting,sculpture,commission,collaboration,other', 'message' => 'required|string|min:10|max:5000', 'privacy' => 'required|accepted', 'website' => 'nullable|prohibited']);
        DB::transaction(function () use ($data) {
            $inquiry = Inquiry::create([...collect($data)->except(['privacy', 'website'])->all(), 'privacy_accepted_at' => now()]);
            NotifyStudio::dispatch('inquiry', $inquiry->id)->onConnection('database')->beforeCommit();
        });

        return back()->with('success', 'Tu mensaje quedó registrado. El estudio se pondrá en contacto contigo.');
    }
}
