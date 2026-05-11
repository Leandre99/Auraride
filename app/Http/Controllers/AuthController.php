<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            $role = Auth::user()->role;
            \App\Models\ActivityLog::log('user_login', "L'utilisateur {$credentials['email']} s'est connecté");
            
            if ($role === 'driver') {
                return redirect()->intended('/driver/dashboard');
            } elseif ($role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Les identifiants fournis ne correspondent pas à nos enregistrements.')->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:client,driver'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'id_card' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        if ($request->role === 'driver') {
            if ($request->hasFile('cv')) {
                $userData['cv_path'] = $request->file('cv')->store('documents/cvs', 'public');
            }
            if ($request->hasFile('id_card')) {
                $userData['id_card_path'] = $request->file('id_card')->store('documents/ids', 'public');
            }
            if ($request->hasFile('photo')) {
                $userData['photo_path'] = $request->file('photo')->store('documents/photos', 'public');
            }
        }

        $user = User::create($userData);

        Auth::login($user);

        \App\Models\ActivityLog::log('user_registered', "Nouvel utilisateur inscrit : {$user->name} ({$user->role})", $user);

        if ($user->role === 'driver') {
            return redirect('/driver/dashboard');
        }

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Models\ActivityLog::log('user_logout', "L'utilisateur " . Auth::user()->name . " s'est déconnecté");
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // À la fin du fichier, avant la dernière accolade

public function editProfile()
{
    $user = auth()->user();
    return view('profile.edit', compact('user'));
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone_number' => 'nullable|string|max:20',
        'current_password' => 'nullable|required_with:password',
        'password' => 'nullable|min:8|confirmed',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;

    if ($request->filled('password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect']);
        }
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
}
}
