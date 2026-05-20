<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $role = $request->query('role');

        if (! in_array($role, ['player', 'owner'], true)) {
            return redirect()->route('choose.role');
        }

        if ($role === 'owner') {
            return redirect()->route('owner.register');
        }

        return view('auth.register', [
            'role' => $role,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role');

        // Generate username for players from email, otherwise use provided username
        if ($role === 'player') {
            $emailPrefix = explode('@', $request->input('email'))[0];
            $baseUsername = Str::lower(trim($emailPrefix));
            $username = $baseUsername;
            $counter = 1;
            
            // Ensure uniqueness
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            $request->merge(['username' => $username]);
        } else {
            $request->merge([
                'username' => Str::lower(trim((string) $request->input('username'))),
            ]);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:player,owner'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Username is required only for owner role
        if ($role === 'owner') {
            $rules['username'] = ['required', 'string', 'max:255', 'regex:/^[a-z0-9_]+$/', 'unique:'.User::class];
        }

        // Add gender validation only for player role
        if ($role === 'player') {
            $rules['gender'] = ['required', 'in:laki-laki,perempuan'];
        }

        $request->validate($rules);

        $avatarProfile = $request->role === 'player' && $request->gender === 'perempuan'
            ? 'profil2.png'
            : 'profil1.png';

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'gender' => $request->role === 'player' ? $request->gender : null,
            'avatar_profile' => $avatarProfile,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
