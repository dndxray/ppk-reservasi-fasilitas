<x-app-layout>
    @php
        $fotoLama = $facility?->foto ? asset('storage/' . $facility->foto) : null;
        $inputClass = 'w-full px-5 py-4 bg-[#F5EFE9] border border-[#E6D6CE] rounded-xl text-base placeholder-gray-400 focus:ring-2 focus:ring-[#511E1D] focus:border-[#511E1D]';
        $sedangEdit = (bool) $facility?->exists;

        // Tipe "alat" memakai Kuantitas; tipe lain memakai Kapasitas
        $tipeAwal = old('tipe', $facility?->tipe ?? '');
        $tipeAlat = $tipeAwal === 'alat';
    @endphp

    <div class="min-h-screen bg-white px-6 sm:px-10 lg:px-12 py-8">

        {{-- judul + panah kembali --}}
        <div class="flex items-center gap-5 mb-6">
            <a href="{{ $sedangEdit ? route('admin.facilities.show', $facility) : route('admin.fasilitas.index') }}"
               class="text-[#47201B] hover:opacity-70 transition" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-black">
                    {{ $sedangEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi data fasilitas.
                    Kolom bertanda <span class="text-red-600 font-bold">*</span> wajib diisi.
                </p>
            </div>
        </div>

        {{-- Banner error dari validasi browser (disembunyikan sampai ada error) --}}
        <div id="banner-error"
             role="alert"
             class="hidden mb-5 rounded-lg border border-[#9B0000] bg-[#FDDEDC] px-6 py-4 text-base font-semibold text-[#9B0000]">
        </div>

        {{-- Banner error dari server (Laravel) --}}
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

        <form id="form-fasilitas"
              method="POST"
              enctype="multipart/form-data"
              novalidate
              action="{{ $sedangEdit
                    ? route('admin.facilities.update', $facility)
                    : route('admin.facilities.store') }}"
              x-data="{ preview: @js($fotoLama), fotoAwal: @js($fotoLama) }"
              class="w-full bg-white border border-[#E0D5D0] rounded-3xl p-6 sm:p-10 space-y-7">

            @csrf
            @if ($sedangEdit)
                @method('PUT')
            @endif

            {{-- Nama Fasilitas --}}
            <div>
                <label for="nama_fasilitas" class="block text-lg font-bold text-black mb-3">
                    Nama Fasilitas <span class="text-red-600">*</span>
                </label>
                <input type="text" id="nama_fasilitas" name="nama_fasilitas"
                       value="{{ old('nama_fasilitas', $facility?->nama_fasilitas ?? '') }}"
                       placeholder="Contoh: Ruang Seminar"
                       autocomplete="off" aria-required="true"
                       class="{{ $inputClass }}">
                <p id="error-nama_fasilitas"
                   class="{{ $errors->has('nama_fasilitas') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('nama_fasilitas') }}</p>
            </div>

            {{-- Tipe --}}
            <div>
                <label for="tipe" class="block text-lg font-bold text-black mb-3">
                    Tipe <span class="text-red-600">*</span>
                </label>
                <select id="tipe" name="tipe" aria-required="true" class="{{ $inputClass }}">
                    <option value="">Pilih tipe</option>
                    @foreach ($tipeOptions as $value => $label)
                        <option value="{{ $value }}" @selected($tipeAwal === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <p id="error-tipe"
                   class="{{ $errors->has('tipe') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('tipe') }}</p>
            </div>

            {{-- Lokasi --}}
            <div>
                <label for="lokasi" class="block text-lg font-bold text-black mb-3">
                    Lokasi Fasilitas <span class="text-red-600">*</span>
                </label>
                <input type="text" id="lokasi" name="lokasi"
                       value="{{ old('lokasi', $facility?->lokasi ?? '') }}"
                       placeholder="Contoh: Gedung B"
                       autocomplete="off" aria-required="true"
                       class="{{ $inputClass }}">
                <p id="error-lokasi"
                   class="{{ $errors->has('lokasi') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('lokasi') }}</p>
            </div>

            {{-- Kapasitas: tampil untuk semua tipe selain alat --}}
            <div id="blok-kapasitas" class="{{ $tipeAlat ? 'hidden' : '' }}">
                <label for="kapasitas" class="block text-lg font-bold text-black mb-3">
                    Kapasitas <span class="text-red-600">*</span>
                </label>
                <input type="text" inputmode="numeric" id="kapasitas" name="kapasitas"
                       value="{{ old('kapasitas', $facility?->kapasitas ?? '') }}"
                       placeholder="Contoh: 50"
                       maxlength="6" autocomplete="off" aria-required="true"
                       class="{{ $inputClass }}">
                <p class="mt-2 text-xs text-gray-400">Jumlah orang yang dapat ditampung.</p>
                <p id="error-kapasitas"
                   class="{{ $errors->has('kapasitas') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('kapasitas') }}</p>
            </div>

            {{-- Kuantitas: tampil hanya untuk tipe alat --}}
            <div id="blok-kuantitas" class="{{ $tipeAlat ? '' : 'hidden' }}">
                <label for="kuantitas" class="block text-lg font-bold text-black mb-3">
                    Kuantitas <span class="text-red-600">*</span>
                </label>
                <input type="text" inputmode="numeric" id="kuantitas" name="kuantitas"
                       value="{{ old('kuantitas', $facility?->kuantitas ?? '') }}"
                       placeholder="Contoh: 10"
                       maxlength="6" autocomplete="off" aria-required="true"
                       class="{{ $inputClass }}">
                <p class="mt-2 text-xs text-gray-400">Jumlah unit alat yang tersedia.</p>
                <p id="error-kuantitas"
                   class="{{ $errors->has('kuantitas') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('kuantitas') }}</p>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-lg font-bold text-black mb-3">
                    Deskripsi <span class="text-red-600">*</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          placeholder="Contoh: Ruangan untuk seminar dan kegiatan akademik"
                          aria-required="true"
                          class="{{ $inputClass }}">{{ old('deskripsi', $facility?->deskripsi ?? '') }}</textarea>
                <p id="error-deskripsi"
                   class="{{ $errors->has('deskripsi') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('deskripsi') }}</p>
            </div>

            {{-- Foto (opsional) --}}
            <div>
                <p class="block text-lg font-bold text-black mb-3">
                    Foto Fasilitas
                    <span class="text-xs font-normal text-[#B23A2E]">(opsional)</span>
                </p>

                <label for="foto" id="area-foto"
                       class="flex flex-col items-center justify-center gap-2 min-h-[160px] px-4 py-6 bg-[#F5EFE9] border border-[#E6D6CE] rounded-xl cursor-pointer hover:bg-[#EFE6DF] transition">

                    <template x-if="preview">
                        <img :src="preview" alt="Preview foto"
                             class="w-full max-w-md h-52 rounded-xl object-cover">
                    </template>

                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span class="text-sm">Klik untuk memilih gambar</span>
                        </div>
                    </template>

                    <span class="text-xs text-gray-400">Hanya menerima JPG, PNG, WEBP (maks. 2 MB)</span>
                </label>

                <input id="foto" type="file" name="foto" accept="image/png,image/jpeg,image/webp"
                       class="hidden"
                       @change="const f = $event.target.files[0]; preview = f ? URL.createObjectURL(f) : fotoAwal">

                <p id="error-foto"
                   class="{{ $errors->has('foto') ? '' : 'hidden' }} mt-2 text-sm font-medium text-[#9B0000]">{{ $errors->first('foto') }}</p>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-6 pt-2">
                <button type="reset" @click="preview = fotoAwal"
                        class="text-base font-bold text-black hover:opacity-70 transition">
                    Reset
                </button>

                <button type="submit" id="btn-simpan"
                        class="px-10 py-4 bg-[#C0453F] text-white text-base font-semibold rounded-xl shadow-sm hover:bg-[#A83B32] transition disabled:opacity-60 disabled:cursor-not-allowed">
                    {{ $sedangEdit ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
                </button>
            </div>

        </form>
    </div>

    {{-- Popup konfirmasi simpan --}}
    <div id="modal-konfirmasi"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
         role="dialog" aria-modal="true" aria-labelledby="modal-judul">

        <div class="relative w-full max-w-md rounded-3xl bg-white px-8 pt-16 pb-8 shadow-2xl">

            <button type="button" id="modal-tutup"
                    class="absolute top-5 right-5 text-black hover:text-[#7B1E1E] transition"
                    aria-label="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <h2 id="modal-judul" class="text-center text-2xl font-bold leading-snug text-black">
                {{ $sedangEdit
                    ? 'Anda yakin ingin menyimpan perubahan fasilitas ini?'
                    : 'Anda yakin ingin menambahkan fasilitas ini?' }}
            </h2>

            <p class="mt-3 text-center text-sm text-gray-500">
                {{ $sedangEdit
                    ? 'Data fasilitas akan diperbarui sesuai isian yang Anda ubah.'
                    : 'Fasilitas baru akan dibuat dengan data yang sudah Anda isi.' }}
            </p>

            <div class="mt-8 grid grid-cols-2 gap-4">
                <button type="button" id="modal-batal"
                        class="h-14 rounded-2xl bg-[#7B3A36] text-base font-bold text-white shadow-md hover:bg-[#5F1717] transition">
                    Batal
                </button>

                <button type="button" id="modal-simpan"
                        class="h-14 rounded-2xl bg-[#F5F0F7] text-base font-bold text-black shadow-md hover:bg-[#EAE0EE] transition">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form        = document.getElementById('form-fasilitas');
            const banner      = document.getElementById('banner-error');
            const modal       = document.getElementById('modal-konfirmasi');
            const btnSimpan   = document.getElementById('btn-simpan');
            const labelSimpan = btnSimpan.textContent.trim();
            const fieldNames  = ['nama_fasilitas', 'tipe', 'lokasi', 'kapasitas', 'kuantitas', 'deskripsi', 'foto'];

            const tipeEl        = document.getElementById('tipe');
            const blokKapasitas = document.getElementById('blok-kapasitas');
            const blokKuantitas = document.getElementById('blok-kuantitas');
            const TIPE_ALAT     = 'alat'; // nilai (value) tipe alat di dropdown

            const tipeFotoOk = ['image/jpeg', 'image/png', 'image/webp'];
            const maxFoto    = 2 * 1024 * 1024; // 2 MB

            // Kapasitas dipakai untuk semua tipe selain alat, kuantitas hanya untuk alat
            function jumlahAktif(name) {
                const alat = tipeEl.value === TIPE_ALAT;
                if (name === 'kapasitas') return !alat;
                if (name === 'kuantitas') return alat;
                return true;
            }

            // Aturan validasi: return '' kalau valid, atau pesan error (bahasa Indonesia)
            const rules = {
                nama_fasilitas(el) {
                    const v = el.value.trim();
                    if (!v) return 'Nama fasilitas wajib diisi.';
                    if (v.length < 3) return 'Nama fasilitas minimal 3 karakter.';
                    if (v.length > 255) return 'Nama fasilitas maksimal 255 karakter.';
                    return '';
                },
                tipe(el) {
                    if (!el.value) return 'Tipe fasilitas wajib dipilih.';
                    return '';
                },
                lokasi(el) {
                    const v = el.value.trim();
                    if (!v) return 'Lokasi fasilitas wajib diisi.';
                    if (v.length < 3) return 'Lokasi fasilitas minimal 3 karakter.';
                    if (v.length > 255) return 'Lokasi fasilitas maksimal 255 karakter.';
                    return '';
                },
                kapasitas(el) {
                    if (!jumlahAktif('kapasitas')) return ''; // tidak dipakai untuk tipe alat
                    const v = el.value.trim();
                    if (!v) return 'Kapasitas wajib diisi.';
                    if (!/^[0-9]+$/.test(v)) return 'Kapasitas harus berupa angka bulat.';
                    if (Number(v) < 1) return 'Kapasitas minimal 1 orang.';
                    return '';
                },
                kuantitas(el) {
                    if (!jumlahAktif('kuantitas')) return ''; // hanya dipakai untuk tipe alat
                    const v = el.value.trim();
                    if (!v) return 'Kuantitas wajib diisi.';
                    if (!/^[0-9]+$/.test(v)) return 'Kuantitas harus berupa angka bulat.';
                    if (Number(v) < 1) return 'Kuantitas minimal 1 unit.';
                    return '';
                },
                deskripsi(el) {
                    const v = el.value.trim();
                    if (!v) return 'Deskripsi wajib diisi.';
                    if (v.length < 10) return 'Deskripsi minimal 10 karakter.';
                    return '';
                },
                foto(el) {
                    const f = el.files[0];
                    if (!f) return ''; // foto opsional
                    if (tipeFotoOk.indexOf(f.type) === -1) return 'Format foto tidak valid. Gunakan JPG, PNG, atau WEBP.';
                    if (f.size > maxFoto) return 'Ukuran foto terlalu besar. Maksimal 2 MB.';
                    return '';
                },
            };

            function setError(name, message) {
                const input  = document.getElementById(name);
                const label  = document.getElementById('error-' + name);
                // Foto: input-nya tersembunyi, jadi yang diberi tanda merah adalah areanya
                const target = name === 'foto' ? document.getElementById('area-foto') : input;

                if (message) {
                    label.textContent = message;
                    label.classList.remove('hidden');
                    target.classList.add('!border-[#9B0000]', '!bg-[#FDF3F2]');
                    input.setAttribute('aria-invalid', 'true');
                } else {
                    label.textContent = '';
                    label.classList.add('hidden');
                    target.classList.remove('!border-[#9B0000]', '!bg-[#FDF3F2]');
                    input.removeAttribute('aria-invalid');
                }
            }

            // Tampilkan Kuantitas untuk alat, Kapasitas untuk tipe lainnya
            function sesuaikanJumlah() {
                const alat = tipeEl.value === TIPE_ALAT;

                blokKapasitas.classList.toggle('hidden', alat);
                blokKuantitas.classList.toggle('hidden', !alat);

                // Bersihkan tanda error pada kolom yang sedang disembunyikan
                setError(alat ? 'kapasitas' : 'kuantitas', '');
            }

            function validateField(name) {
                const message = rules[name](document.getElementById(name));
                setError(name, message);
                return message;
            }

            function validateAll() {
                const errors = [];
                fieldNames.forEach(function (name) {
                    const message = validateField(name);
                    if (message) errors.push({ name: name, message: message });
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
                banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

            // Kapasitas & kuantitas: hanya angka
            ['kapasitas', 'kuantitas'].forEach(function (id) {
                document.getElementById(id).addEventListener('input', function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            // Ganti tipe -> ganti kolom jumlah yang tampil
            tipeEl.addEventListener('change', sesuaikanJumlah);

            // Validasi saat keluar dari kolom, dan hapus error begitu sudah benar
            fieldNames.forEach(function (name) {
                if (name === 'foto') return;
                const input = document.getElementById(name);

                input.addEventListener('blur', function () { validateField(name); });

                ['input', 'change'].forEach(function (evt) {
                    input.addEventListener(evt, function () {
                        if (!rules[name](input)) setError(name, '');
                    });
                });
            });

            // Foto: cek format & ukuran begitu dipilih; kalau tidak valid, pilihan dibatalkan
            document.getElementById('foto').addEventListener('change', function () {
                const message = rules.foto(this);
                if (message) {
                    this.value = '';
                    this.dispatchEvent(new Event('change')); // agar preview kembali ke foto awal
                }
                setError('foto', message);
            });

            // Tombol Reset: bersihkan semua tanda error dan sesuaikan ulang kolom jumlah
            form.addEventListener('reset', function () {
                fieldNames.forEach(function (name) { setError(name, ''); });
                showBanner([]);
                setTimeout(sesuaikanJumlah, 0); // tunggu nilai form kembali ke awal
            });

            // Klik simpan -> validasi dulu, kalau lolos tampilkan popup konfirmasi
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const errors = validateAll();
                showBanner(errors);

                if (errors.length > 0) {
                    const pertama = errors[0].name;
                    if (pertama === 'foto') {
                        document.getElementById('area-foto').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        document.getElementById(pertama).focus({ preventScroll: true });
                    }
                    return;
                }

                bukaModal();
            });

            // Konfirmasi di popup
            document.getElementById('modal-simpan').addEventListener('click', function () {
                tutupModal();
                btnSimpan.disabled = true;
                btnSimpan.textContent = 'Menyimpan...';
                form.submit(); // submit native tidak memicu event 'submit' lagi
            });

            document.getElementById('modal-batal').addEventListener('click', tutupModal);
            document.getElementById('modal-tutup').addEventListener('click', tutupModal);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) tutupModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) tutupModal();
            });

            // Kalau halaman dibuka lewat tombol "Kembali" browser, pulihkan tombol simpan
            window.addEventListener('pageshow', function () {
                btnSimpan.disabled = false;
                btnSimpan.textContent = labelSimpan;
                sesuaikanJumlah();
            });

            sesuaikanJumlah();
        })();
    </script>
</x-app-layout>