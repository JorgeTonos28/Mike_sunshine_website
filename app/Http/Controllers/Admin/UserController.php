<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Users', ['users' => User::select(['id', 'name', 'email', 'role', 'is_active', 'created_at'])->latest()->get()]);
    }

    public function store(Request $request)
    {
        return $this->save($request, new User);
    }

    public function update(Request $request, User $user)
    {
        return $this->save($request, $user);
    }

    private function save(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:180', 'email' => ['required', 'email', 'lowercase', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => 'required|in:admin,editor', 'is_active' => 'required|boolean',
            'password' => [$user->exists ? 'nullable' : 'required', 'max:128', Password::min(12)->mixedCase()->numbers()],
        ]);
        DB::transaction(function () use ($user, $data) {
            $admins = User::where('role', 'admin')->where('is_active', true)->lockForUpdate()->get();
            if ($user->exists && $user->isAdmin() && ($data['role'] !== 'admin' || ! $data['is_active']) && $admins->count() <= 1) {
                throw ValidationException::withMessages(['role' => 'Debe quedar al menos un administrador activo.']);
            }
            if (empty($data['password'])) {
                unset($data['password']);
            }
            if ($user->exists && $data['email'] !== $user->email) {
                $user->google_id = null;
                $user->email_verified_at = null;
            }
            $user->forceFill($data)->save();
            // Revocation applies immediately to sessions and persistent login cookies.
            if (! $user->is_active || isset($data['password'])) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
                $user->forceFill(['remember_token' => Str::random(60)])->save();
            }
        });

        return back()->with('success', 'Usuario guardado.');
    }
}
