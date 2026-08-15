<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins', 'username')->ignore($admin->id),
            ],
        ]);

        $admin->update($data);

        return back()->with('success', 'Vos informations ont été mises à jour.');
    }

    public function changePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($data['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $admin->update(['password' => $data['password']]);

        return back()->with('success', 'Votre mot de passe a été modifié.');
    }
}
