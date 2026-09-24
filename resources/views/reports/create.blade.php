<x-app-layout>

    <div class="py-6 max-w-4xl mx-auto">

        {{-- tombol back & judul --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('reports.index') }}" 
               class="text-[#3D1513] hover:opacity-75 transition p-1 inline-flex items-center"
               title="Kembali ke Laporan Saya">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#3D1513]" style="font-family: 'Poppins', sans-serif;">
                Laporkan Kerusakan Fasilitas
            </h1>
        </div>

        {{-- notif sukses --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center shadow-sm">
                <svg class="h-5 w-5 mr-3 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- error validasi --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
                <div class="flex items-center mb-1">
                    <svg class="h-5 w-5 mr-2 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-semibold">Terdapat kesalahan pada isian form:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-7">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- kartu form utama --}}
        <div class="bg-white rounded-2xl border border-[#E5E0DF] p-6 sm:p-8 md:p-10 shadow-sm"
             x-data="{
                confirmOpen: false,
                imagePreview: null,
                fileName: '',
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                        const reader = new FileReader();
                        reader.onload = (e) => { this.imagePreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                },
                removeFile() {
                    this.imagePreview = null;
                    this.fileName = '';
                    document.getElementById('foto').value = '';
                },
                resetForm() {
                    this.removeFile();
                }
             }">

            <form id="form-lapor"
                  action="{{ route('reports.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">

                @csrf

                {{-- pilihan fasilitas --}}
                <div>
                    <label for="facility_id" class="block font-bold text-sm text-[#262626] mb-2">
                        Nama Fasilitas
                    </label>

                    <div class="relative">
                        <select name="facility_id"
                                id="facility_id"
                                required
                                class="block w-full border border-[#E5DFDD] rounded-lg py-3.5 px-4 bg-[#F5EBE9]/60 text-gray-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#A94438]/20 focus:border-[#A94438] outline-none transition appearance-none">
                            <option value="">-- Pilih Fasilitas --</option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}" @selected(old('facility_id') == $facility->id)>
                                    {{ $facility->nama_fasilitas }} ({{ $facility->lokasi }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    @if ($facilities->isEmpty())
                        <p class="text-xs text-red-600 mt-1.5">
                            Belum ada fasilitas yang tersedia untuk dilaporkan.
                        </p>
                    @endif

                    @error('facility_id')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- kategori & tgl ditemukan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- kategori --}}
                    <div>
                        <label for="kategori" class="block font-bold text-sm text-[#262626] mb-2">
                            Kategori Kerusakan
                        </label>
                        <input type="text"
                               name="kategori"
                               id="kategori"
                               required
                               maxlength="100"
                               value="{{ old('kategori') }}"
                               placeholder="Contoh: Listrik, AC, Proyektor, Kursi..."
                               class="block w-full border border-[#E5DFDD] rounded-lg py-3.5 px-4 bg-[#F5EBE9]/60 text-gray-800 text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-[#A94438]/20 focus:border-[#A94438] outline-none transition">

                        @error('kategori')
                            <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- tgl ditemukan --}}
                    <div>
                        <label for="tanggal_ditemukan" class="block font-bold text-sm text-[#262626] mb-2">
                            Tanggal Ditemukan
                        </label>
                        <input type="date"
                               name="tanggal_ditemukan"
                               id="tanggal_ditemukan"
                               value="{{ old('tanggal_ditemukan', date('Y-m-d')) }}"
                               class="block w-full border border-[#E5DFDD] rounded-lg py-3.5 px-4 bg-[#F5EBE9]/60 text-gray-800 text-sm focus:bg-white focus:ring-2 focus:ring-[#A94438]/20 focus:border-[#A94438] outline-none transition">

                        @error('tanggal_ditemukan')
                            <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- deskripsi kerusakan --}}
                <div>
                    <label for="deskripsi" class="block font-bold text-sm text-[#262626] mb-2">
                        Deskripsi Kerusakan
                    </label>
                    <textarea name="deskripsi"
                              id="deskripsi"
                              rows="4"
                              required
                              maxlength="1000"
                              placeholder="Jelaskan detail kerusakan yang Anda temukan..."
                              class="block w-full border border-[#E5DFDD] rounded-lg p-4 bg-[#F5EBE9]/60 text-gray-800 text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-[#A94438]/20 focus:border-[#A94438] outline-none transition leading-relaxed">{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- upload foto --}}
                <div>
                    <label class="block font-bold text-sm text-[#262626] mb-2">
                        Foto Kondisi Kerusakan
                    </label>

                    {{-- kotak upload foto --}}
                    <div class="relative border border-[#E5DFDD] rounded-lg bg-[#F5EBE9]/60 hover:bg-[#F5EBE9]/90 transition p-6 text-center cursor-pointer min-h-[130px] flex flex-col items-center justify-center group"
                         @click="$refs.fileInput.click()">

                        {{-- kalau belum pilih foto --}}
                        <template x-if="!imagePreview">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center text-[#A94438] shadow-2xs group-hover:scale-105 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">
                                        Klik atau seret foto kerusakan ke sini
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Maksimal 2MB (Format: JPG, JPEG, PNG)
                                    </p>
                                </div>
                            </div>
                        </template>

                        {{-- preview foto yang dipilih --}}
                        <template x-if="imagePreview">
                            <div class="flex items-center gap-4 w-full px-2" @click.stop>
                                <img :src="imagePreview" alt="Preview Foto" class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-xs">
                                <div class="flex-1 text-left min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate" x-text="fileName"></p>
                                    <p class="text-xs text-green-600 font-medium mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Foto siap diunggah
                                    </p>
                                    <div class="mt-2 flex items-center gap-3">
                                        <button type="button" @click="$refs.fileInput.click()" class="text-xs font-semibold text-[#A94438] hover:underline">
                                            Ganti Foto
                                        </button>
                                        <span class="text-gray-300">|</span>
                                        <button type="button" @click="removeFile()" class="text-xs font-semibold text-gray-500 hover:text-red-600">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <input type="file"
                               name="foto"
                               id="foto"
                               x-ref="fileInput"
                               accept="image/*"
                               @change="handleFileSelect"
                               class="hidden">
                    </div>

                    @error('foto')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- tombol reset & kirim --}}
                <div class="flex items-center justify-end gap-6 pt-4">
                    <button type="reset"
                            @click="resetForm()"
                            class="text-sm font-bold text-[#262626] hover:text-[#A94438] transition px-3 py-2.5">
                        Reset
                    </button>

                    <button type="button"
                            @click="confirmOpen = true"
                            class="bg-[#B3392E] hover:bg-[#9B2F25] text-white text-sm font-semibold px-8 py-3 rounded-xl shadow-sm hover:shadow transition">
                        Kirim Laporan
                    </button>
                </div>

            </form>

            {{-- modal konfirmasi --}}
            <div x-show="confirmOpen"
                 x-cloak
                 class="fixed inset-0 bg-black/50 backdrop-blur-2xs flex items-center justify-center z-50 p-4">

                <div @click.outside="confirmOpen = false"
                     class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 w-full max-w-md relative text-center">

                    <div class="w-14 h-14 rounded-full bg-red-50 text-[#B3392E] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        Konfirmasi Kirim Laporan
                    </h3>

                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        Apakah Anda yakin data laporan kerusakan yang Anda masukkan sudah benar dan ingin mengirimkannya sekarang?
                    </p>

                    <div class="flex items-center justify-center gap-3">
                        <button type="button"
                                @click="confirmOpen = false"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm rounded-xl transition">
                            Periksa Kembali
                        </button>

                        <button type="button"
                                @click="document.getElementById('form-lapor').submit()"
                                class="px-6 py-2.5 bg-[#B3392E] hover:bg-[#9B2F25] text-white font-semibold text-sm rounded-xl transition shadow-xs">
                            Ya, Kirim Laporan
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>