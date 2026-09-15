<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan smeua user
    public function indexUsers(Request $request)
    {
        $search = $request->search;

        $users = User::where('role', 'pengguna')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengguna.index', compact('users', 'search'));
    }

    public function indexStaff(Request $request)
    {
        $search = $request->search;

        $staff = User::where('role', 'petugas')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.petugas.index', compact('staff', 'search'));
    }
    
    // Mendaftarkan petugas
    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nim_nip' => 'nullable|string|max:50',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $validated['role'] = 'petugas';
        $validated['status_verifikasi'] = 'terverifikasi';

        $user = User::create($validated);

        return response()->json([
            'message' => 'Petugas berhasil didaftarkan',
            'data' => $user,
        ], 201);
    }

    // Mendaftarkan pengguna
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nim_nip' => 'nullable|string|max:50',
            'no_telepon' => 'nullable|string|max:20'
        ]);

        $validated['role'] = 'pengguna';
        $validated['status_verifikasi'] = 'terverifikasi';
        $user = User::create($validated);

        return response()->json([
            'message'=>'Pengguna berhasil didaftarkan',
            'data' => $user,
        ], 201);
    }
    //Verifikasi pengguna
    public function verify(User $user)
    {
        $user->update([
            'status_verifikasi' => 'terverifikasi',
        ]);

        return response()->json([
            'message' => 'Pengguna berhasil diverifikasi',
            'data' => $user,
        ]);
    }

    //Menolak pengguna
    public function reject(User $user)
    {
        $user->update([
            'status_verifikasi' => 'ditolak',
        ]);

        return response()->json([
            'message' => 'Pendaftaran pengguna ditolak',
            'data' => $user,
        ]);
    }
    public function createUser()
    {
        return view('admin.pengguna.create');
    }

    public function createStaff()
    {
        return view('admin.petugas.create');
    }
}
