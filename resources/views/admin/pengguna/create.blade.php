
<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="w-full px-6 sm:px-10 lg:px-12 py-8"
         style="font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;">

        {{-- Header --}}
        <div class="flex items-center gap-5 mb-6">
            <a href="{{ route('admin.pengguna.index') }}"
               class="text-[#5A2A27] hover:text-[#7B1E1E] transition"
               aria-label="Kembali">
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h1 class="text-3xl font-bold text-black">Tambah Pengguna</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi data pengguna untuk menambahkan akun baru.
                    Kolom bertanda <span class="text-red-600 font-bold">*</span> wajib diisi.
                </p>
            </div>
        </div>

        {{-- Banner error JavaScript --}}
        <div id="banner-error"
             role="alert"
             class="hidden mb-5 rounded-lg border border-[#9B0000] bg-[#FDDEDC] px-6 py-4 text-base font-semibold text-[#9B0000]">
        </div>

        {{-- Banner error Laravel --}}
        @if ($errors->any())
            <div role="alert"
                 class="mb-5 rounded-lg border border-[#9B0000] bg-[#FDDEDC] px-6 py-4 text-base font-semibold text-[#9B0000]">
                @if ($errors->count() === 1)
                    {{ $errors->first() }}
                @else
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        {{-- Form --}}
        <div class="w-full bg-white rounded-3xl border border-gray-300 px-6 py-8 sm:px-12 sm:py-12">

            <form id="form-pengguna"
                  action="{{ route('admin.pengguna.store') }}"
                  method="POST"
                  novalidate>
                @csrf

                {{-- Nama --}}
                <div class="mb-6">
                    <label for="name" class="block text-base font-bold text-black mb-3">
                        Nama Lengkap <span class="text-red-600">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="off"
                        aria-required="true"
                        class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                    <p id="error-name" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                </div>

                {{-- Email --}}
                <div class="mb-6">
                    <label for="email" class="block text-base font-bold text-black mb-3">
                        Email <span class="text-red-600">*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        autocomplete="off"
                        aria-required="true"
                        class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                    <p id="error-email" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                </div>

                {{-- Jenis Pengguna --}}
                <div class="mb-6">
                    <label for="jenis_pengguna" class="block text-base font-bold text-black mb-3">
                        Jenis Pengguna <span class="text-red-600">*</span>
                    </label>

                    <select
                        id="jenis_pengguna"
                        name="jenis_pengguna"
                        aria-required="true"
                        class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                        <option value="">Pilih jenis pengguna</option>
                        <option value="mahasiswa" @selected(old('jenis_pengguna') === 'mahasiswa')>
                            Mahasiswa
                        </option>
                        <option value="dosen" @selected(old('jenis_pengguna') === 'dosen')>
                            Dosen
                        </option>
                        <option value="staf" @selected(old('jenis_pengguna') === 'staf')>
                            Staf
                        </option>
                    </select>

                    <p id="error-jenis_pengguna" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                </div>

                {{-- Nomor Identitas dan Nomor Telepon --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-[60px] gap-y-6 mb-6">

                    <div>
                        <label for="nim_nip" class="block text-base font-bold text-black mb-3">
                            Nomor Identitas (NIM/NIP/NIK Pegawai)
                        </label>

                        <input
                            type="text"
                            id="nim_nip"
                            name="nim_nip"
                            value="{{ old('nim_nip') }}"
                            placeholder="Masukkan nomor identitas jika tersedia"
                            maxlength="50"
                            autocomplete="off"
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                        <p id="error-nim_nip" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-base font-bold text-black mb-3">
                            Nomor Telepon <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="tel"
                            inputmode="tel"
                            id="no_telepon"
                            name="no_telepon"
                            value="{{ old('no_telepon') }}"
                            placeholder="08xxxxxxxxxx"
                            maxlength="15"
                            autocomplete="off"
                            aria-required="true"
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                        <p id="error-no_telepon" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                    </div>
                </div>

                {{-- Password dan Konfirmasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-[60px] gap-y-6 mb-10">

                    <div>
                        <label for="password" class="block text-base font-bold text-black mb-3">
                            Password <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="new-password"
                            aria-required="true"
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                        <p id="error-password" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-base font-bold text-black mb-3">
                            Konfirmasi Password <span class="text-red-600">*</span>
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                            aria-required="true"
                            class="w-full h-14 px-6 rounded-xl border border-[#DCC9C5] bg-[#F5EEEB] text-base text-gray-900 placeholder-gray-500 focus:border-[#7B1E1E] focus:ring-1 focus:ring-[#7B1E1E] focus:bg-white">

                        <p id="error-password_confirmation" class="hidden mt-2 text-sm font-medium text-[#9B0000]"></p>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.pengguna.index') }}"
                       class="px-8 h-12 inline-flex items-center rounded-xl border border-[#DCC9C5] text-[#5A2A27] font-medium hover:bg-[#F5EEEB] transition">
                        Batal
                    </a>

                    <button
                        type="submit"
                        id="btn-simpan"
                        class="px-8 h-12 rounded-xl bg-[#7B1E1E] text-white font-medium hover:bg-[#5F1717] transition disabled:opacity-60 disabled:cursor-not-allowed">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Popup konfirmasi --}}
    <div id="modal-konfirmasi"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-judul"
         style="font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;">

        <div class="relative w-full max-w-md rounded-3xl bg-white px-8 pt-16 pb-8 shadow-2xl">

            <button type="button"
                    id="modal-tutup"
                    class="absolute top-5 right-5 text-black hover:text-[#7B1E1E] transition"
                    aria-label="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <h2 id="modal-judul" class="text-center text-2xl font-bold leading-snug text-black">
                Anda yakin ingin menambahkan pengguna ini?
            </h2>

            <p class="mt-3 text-center text-sm text-gray-500">
                Akun pengguna baru akan dibuat dengan data yang sudah Anda isi.
            </p>

            <div class="mt-8 grid grid-cols-2 gap-4">
                <button type="button"
                        id="modal-batal"
                        class="h-14 rounded-2xl bg-[#7B3A36] text-base font-bold text-white shadow-md hover:bg-[#5F1717] transition">
                    Batal
                </button>

                <button type="button"
                        id="modal-simpan"
                        class="h-14 rounded-2xl bg-[#F5F0F7] text-base font-bold text-black shadow-md hover:bg-[#EAE0EE] transition">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('form-pengguna');
            const banner = document.getElementById('banner-error');
            const modal = document.getElementById('modal-konfirmasi');
            const btnSimpan = document.getElementById('btn-simpan');

            const fieldNames = [
                'name',
                'email',
                'jenis_pengguna',
                'nim_nip',
                'no_telepon',
                'password',
                'password_confirmation'
            ];

            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            const regexTelp = /^(\+62|62|0)8[1-9][0-9]{7,10}$/;

            const rules = {
                name(v) {
                    v = v.trim();

                    if (!v) return 'Nama lengkap wajib diisi.';
                    if (v.length < 3) return 'Nama lengkap minimal 3 karakter.';

                    return '';
                },

                email(v) {
                    v = v.trim();

                    if (!v) return 'Email wajib diisi.';
                    if (!regexEmail.test(v)) return 'Format email tidak valid. Contoh: nama@email.com';

                    return '';
                },

                jenis_pengguna(v) {
                    if (!v) return 'Jenis pengguna wajib dipilih.';

                    if (!['mahasiswa', 'dosen', 'staf'].includes(v)) {
                        return 'Jenis pengguna tidak valid.';
                    }

                    return '';
                },

                nim_nip(v) {
                    v = v.trim();

                    if (v.length > 50) {
                        return 'Nomor identitas maksimal 50 karakter.';
                    }

                    return '';
                },

                no_telepon(v) {
                    v = v.trim();

                    if (!v) return 'Nomor telepon wajib diisi.';

                    if (!regexTelp.test(v)) {
                        return 'Nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx.';
                    }

                    return '';
                },

                password(v) {
                    if (!v) return 'Password wajib diisi.';
                    if (v.length < 8) return 'Password minimal 8 karakter.';

                    return '';
                },

                password_confirmation(v) {
                    if (!v) return 'Konfirmasi password wajib diisi.';

                    if (v !== document.getElementById('password').value) {
                        return 'Konfirmasi password tidak sama dengan password.';
                    }

                    return '';
                }
            };

            function setError(name, message) {
                const input = document.getElementById(name);
                const label = document.getElementById('error-' + name);

                if (message) {
                    label.textContent = message;
                    label.classList.remove('hidden');
                    input.classList.add('!border-[#9B0000]', '!bg-[#FDF3F2]');
                    input.setAttribute('aria-invalid', 'true');
                } else {
                    label.textContent = '';
                    label.classList.add('hidden');
                    input.classList.remove('!border-[#9B0000]', '!bg-[#FDF3F2]');
                    input.removeAttribute('aria-invalid');
                }
            }

            function validateField(name) {
                const message = rules[name](document.getElementById(name).value);
                setError(name, message);

                return message;
            }

            function validateAll() {
                const errors = [];

                fieldNames.forEach(function (name) {
                    const message = validateField(name);

                    if (message) {
                        errors.push({
                            name: name,
                            message: message
                        });
                    }
                });

                return errors;
            }

            function showBanner(errors) {
                if (errors.length === 0) {
                    banner.classList.add('hidden');
                    banner.textContent = '';
                    return;
                }

                banner.textContent = errors.length === 1
                    ? errors[0].message
                    : 'Data belum lengkap atau tidak valid. Periksa kembali kolom yang ditandai merah.';

                banner.classList.remove('hidden');
                banner.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            function bukaModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.getElementById('modal-batal').focus();
            }

            function tutupModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Nomor telepon: hanya angka dan tanda + di awal
            document.getElementById('no_telepon').addEventListener('input', function () {
                let v = this.value.replace(/[^0-9+]/g, '');

                if (v.charAt(0) === '+') {
                    v = '+' + v.slice(1).replace(/\+/g, '');
                } else {
                    v = v.replace(/\+/g, '');
                }

                this.value = v;
            });

            // Validasi saat kolom diisi atau ditinggalkan
            fieldNames.forEach(function (name) {
                const input = document.getElementById(name);

                input.addEventListener('blur', function () {
                    validateField(name);
                });

                input.addEventListener('input', function () {
                    if (!rules[name](input.value)) {
                        setError(name, '');
                    }

                    if (
                        name === 'password' &&
                        document.getElementById('password_confirmation').value
                    ) {
                        validateField('password_confirmation');
                    }
                });

                input.addEventListener('change', function () {
                    validateField(name);
                });
            });

            // Validasi sebelum popup ditampilkan
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const errors = validateAll();
                showBanner(errors);

                if (errors.length > 0) {
                    document.getElementById(errors[0].name).focus({
                        preventScroll: true
                    });

                    return;
                }

                bukaModal();
            });

            // Konfirmasi simpan
            document.getElementById('modal-simpan').addEventListener('click', function () {
                tutupModal();
                btnSimpan.disabled = true;
                btnSimpan.textContent = 'Menyimpan...';

                form.submit();
            });

            document.getElementById('modal-batal').addEventListener('click', tutupModal);
            document.getElementById('modal-tutup').addEventListener('click', tutupModal);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    tutupModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    tutupModal();
                }
            });
        })();
    </script>
</x-app-layout>
