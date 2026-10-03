<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan smeua user
    public function indexUsers(Request $request)
    {
        $search  = trim((string) $request->input('search'));
        $status  = in_array($request->input('status'), ['menunggu', 'aktif', 'nonaktif', 'ditolak'], true) ? $request->input('status') : null;
        $tanggal = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->input('tanggal')) ? $request->input('tanggal') : null;

        // Nilai filter/label di layar -> nilai di database
        $statusDb = [
            'menunggu' => 'menunggu',
            'aktif'    => 'terverifikasi',
            'nonaktif' => 'nonaktif',
            'ditolak'  => 'ditolak',
        ];

        $users = User::where('role', 'pengguna')
            ->when($search !== '', function ($query) use ($search, $statusDb) {
                $keyword = mb_strtolower($search);
                $like    = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like, $keyword, $statusDb) {
                    foreach (['name', 'email', 'nim_nip', 'no_telepon'] as $kolom) {
                        $q->orWhereRaw("LOWER($kolom) LIKE ?", [$like]);
                    }

                    // Pencarian berdasarkan label status (minimal 3 huruf, mis. "akt", "non", "men")
                    if (mb_strlen($keyword) >= 3) {
                        foreach ($statusDb as $label => $nilai) {
                            if (str_starts_with($label, $keyword)) {
                                $q->orWhere('status_verifikasi', $nilai);
                            }
                        }
                    }
                });
            })
            ->when($status, fn ($q) => $q->where('status_verifikasi', $statusDb[$status]))
            ->when($tanggal, fn ($q) => $q->whereDate('created_at', $tanggal))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengguna.index', compact('users', 'search', 'status', 'tanggal'));
    }


    public function indexStaff(Request $request)
    {
        $search  = trim((string) $request->input('search'));
        $status  = in_array($request->input('status'), ['aktif', 'nonaktif'], true) ? $request->input('status') : null;
        $tanggal = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->input('tanggal')) ? $request->input('tanggal') : null;

        $staff = User::where('role', 'petugas')
            ->when($search !== '', function ($query) use ($search) {
                $keyword = mb_strtolower($search);
                $like    = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like, $keyword) {
                    foreach (['name', 'email', 'nim_nip', 'no_telepon'] as $kolom) {
                        $q->orWhereRaw("LOWER($kolom) LIKE ?", [$like]);
                    }

                    // Pencarian berdasarkan label status
                    if ($keyword === 'aktif') {
                        $q->orWhere('status_verifikasi', 'terverifikasi');
                    } elseif ($keyword === 'nonaktif') {
                        $q->orWhere('status_verifikasi', 'nonaktif');
                    }
                });
            })
            ->when($status === 'aktif', fn ($q) => $q->where('status_verifikasi', 'terverifikasi'))
            ->when($status === 'nonaktif', fn ($q) => $q->where('status_verifikasi', 'nonaktif'))
            ->when($tanggal, fn ($q) => $q->whereDate('created_at', $tanggal))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.petugas.index', compact('staff', 'search', 'status', 'tanggal'));
    }

    public function showStaff(User $user)
    {
        abort_unless($user->role === 'petugas', 404);

        return view('admin.petugas.show', ['petugas' => $user]);
    }

    public function deactivateStaff(User $user)
    {
        abort_unless($user->role === 'petugas', 404);

        $user->update(['status_verifikasi' => 'nonaktif']);

        return redirect()
            ->route('admin.petugas.show', $user)
            ->with('success', 'Akun petugas berhasil dinonaktifkan.');
    }

    public function activateStaff(User $user)
    {
        abort_unless($user->role === 'petugas', 404);

        $user->update(['status_verifikasi' => 'terverifikasi']);

        return redirect()
            ->route('admin.petugas.show', $user)
            ->with('success', 'Akun petugas berhasil diaktifkan kembali.');
    }
    
    // Mendaftarkan petugas
// Mendaftarkan petugas
public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|min:3|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'no_telepon' => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{7,10}$/'],
            'password'   => 'required|string|min:8|confirmed',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'name.min'            => 'Nama lengkap minimal 3 karakter.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah terdaftar.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex'    => 'Nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak sama dengan password.',
        ]);

        // Konfirmasi password hanya untuk validasi, tidak disimpan
        unset($validated['password_confirmation']);

        $validated['role'] = 'petugas';
        $validated['status_verifikasi'] = 'terverifikasi';

        $user = User::create($validated);

        // Tetap kirim JSON kalau dipanggil dari API (Postman, aplikasi mobile, dll)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Petugas berhasil didaftarkan',
                'data'    => $user,
            ], 201);
        }

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    // Mendaftarkan pengguna
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|min:3|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'nim_nip'    => ['required', 'regex:/^[0-9]{5,20}$/'],
            'no_telepon' => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{7,10}$/'],
            'password'   => 'required|string|min:8|confirmed',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'name.min'            => 'Nama lengkap minimal 3 karakter.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah terdaftar.',
            'nim_nip.required'    => 'NIM wajib diisi.',
            'nim_nip.regex'       => 'NIM tidak valid. Gunakan angka saja (5-20 digit).',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex'    => 'Nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak sama dengan password.',
        ]);

        unset($validated['password_confirmation']);

        $validated['role'] = 'pengguna';
        $validated['status_verifikasi'] = 'terverifikasi';

        User::create($validated);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }
    //Verifikasi pengguna
    public function verify(Request $request, User $user)
    {
        $user->update(['status_verifikasi' => 'terverifikasi']);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengguna berhasil diverifikasi', 'data' => $user]);
        }

        return back()->with('success', 'Pengguna berhasil diverifikasi.');
    }

    //Menolak pengguna
    public function reject(Request $request, User $user)
    {
        $user->update(['status_verifikasi' => 'ditolak']);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pendaftaran pengguna ditolak', 'data' => $user]);
        }

        return back()->with('success', 'Pendaftaran pengguna ditolak.');
    }
    public function createUser()
    {
        return view('admin.pengguna.create');
    }

    public function createStaff()
    {
        return view('admin.petugas.create');
    }

    public function showUser(User $user)
    {
        $reservations = Reservation::with('facility')
            ->where('user_id', $user->id)
            ->latest('tanggal')
            ->latest('waktu_mulai')
            ->paginate(10, ['*'], 'reservasi'); // 'reservasi' = nama parameter halaman, supaya tidak bentrok

        return view('admin.pengguna.show', [   // sesuaikan dengan view dan variabel Anda yang sudah ada
            'pengguna'     => $user,
            'reservations' => $reservations,
        ]);
    }

    public function deactivateUser(User $user)
    {
        abort_unless($user->role === 'pengguna', 404);

        $user->update(['status_verifikasi' => 'nonaktif']);

        return redirect()
            ->route('admin.pengguna.show', $user)
            ->with('success', 'Akun pengguna berhasil dinonaktifkan.');
    }

    public function activateUser(User $user)
    {
        abort_unless($user->role === 'pengguna', 404);

        $user->update(['status_verifikasi' => 'terverifikasi']);

        return redirect()
            ->route('admin.pengguna.show', $user)
            ->with('success', 'Akun pengguna berhasil diaktifkan kembali.');
    }
}
