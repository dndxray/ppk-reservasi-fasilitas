<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Samakan format nomor telepon sebelum divalidasi
        // (0812..., 62812..., +62812... -> 0812...)
        $request->merge([
            'no_telepon' => User::normalisasiTelepon($request->input('no_telepon')),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Ubah 'nullable' jadi 'required' kalau form registrasi sudah punya inputnya
            'jenis_pengguna' => ['nullable', Rule::in(['mahasiswa', 'dosen', 'staf'])],
            'nim_nip' => ['nullable', 'string', 'max:50'],
            'no_telepon' => [
                'nullable',
                'regex:/^08[1-9][0-9]{7,10}$/',
                Rule::unique(User::class, 'no_telepon'),
            ],
        ], [
            'no_telepon.regex'  => 'Nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx.',
            'no_telepon.unique' => 'Nomor telepon ini sudah terdaftar.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengguna',
            'status_verifikasi' => 'menunggu',
            'jenis_pengguna' => $request->jenis_pengguna,
            'nim_nip' => $request->nim_nip,
            'no_telepon' => $request->no_telepon,
        ]);

        event(new Registered($user));

        return redirect(route('login'))
            ->with('status', 'Registrasi berhasil! Akun kamu sedang menunggu verifikasi admin sebelum bisa digunakan untuk login.');
    }
}