<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Update username / display name.
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => [
                'required', 'string', 'max:50',
                // unique but ignore own id
                \Illuminate\Validation\Rule::unique('users', 'username')->ignore($user->id),
            ],
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'username.max'      => 'Username maksimal 50 karakter.',
            'username.unique'   => 'Username ini sudah digunakan.',
        ]);

        $user->username = $validated['username'];
        $user->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Username berhasil diperbarui.',
            'username' => $user->username,
        ]);
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(6)],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai.',
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}
