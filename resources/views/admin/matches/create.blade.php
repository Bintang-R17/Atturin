@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('admin.matches.index') }}" class="hover:text-brand-500 transition-colors">Dashboard</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600">Buat Event Baru</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Buat Event Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Tentukan jadwal, kapasitas, dan skema iuran sebelum join link dibuka.</p>
    </div>

    <div class="pro-card overflow-hidden">
        <form action="{{ route('admin.matches.store') }}" method="POST">
            @csrf

            {{-- Section: Event Info --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">Informasi Event</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="nama_event" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Event</label>
                        <input type="text" name="nama_event" id="nama_event" value="{{ old('nama_event') }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                               required placeholder="Cth: Friendly Match Weekend">
                        @error('nama_event')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori / Jenis Olahraga</label>
                                <input type="text" name="kategori" id="kategori" value="{{ old('kategori', 'Olahraga') }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                               placeholder="Cth: Sepak Bola, Basket, Badminton, dll">
                        @error('kategori')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                                   required>
                        </div>
                        <div>
                            <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu</label>
                            <input type="time" name="waktu" id="waktu" value="{{ old('waktu') }}"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label for="tempat" class="block text-sm font-medium text-gray-700 mb-1.5">Tempat (Venue)</label>
                        <input type="text" name="tempat" id="tempat" value="{{ old('tempat') }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                               required placeholder="Cth: Arena Sport Center">
                        @error('tempat')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="link_maps" class="block text-sm font-medium text-gray-700 mb-1.5">Link Google Maps (Opsional)</label>
                        <input type="url" name="link_maps" id="link_maps" value="{{ old('link_maps') }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                               placeholder="Cth: https://maps.app.goo.gl/...">
                        @error('link_maps')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slot_max" class="block text-sm font-medium text-gray-700 mb-1.5">Maksimal Slot Pemain</label>
                        <input type="number" name="slot_max" id="slot_max" min="1" value="{{ old('slot_max') }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                               required placeholder="Cth: 15">
                    </div>
                </div>
            </div>

            {{-- Section: Payment --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">Pengaturan Pembayaran</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all" required>
                            <option value="tunai" {{ old('metode_pembayaran', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="online_banking" {{ old('metode_pembayaran') === 'online_banking' ? 'selected' : '' }}>Online Banking (Simulasi Midtrans)</option>
                        </select>
                        @error('metode_pembayaran')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="iuran_per_pemain" class="block text-sm font-medium text-gray-700 mb-1.5">Iuran per Pemain (Rp)</label>
                        <input type="number" name="iuran_per_pemain" id="iuran_per_pemain" min="0" step="1000" value="{{ old('iuran_per_pemain', 0) }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition-all"
                               required placeholder="Contoh: 50000">
                        @error('iuran_per_pemain')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section: Privacy --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">Pengaturan Privasi Halaman Join</h3>
                </div>

                <div class="space-y-4">
                    <input type="hidden" name="show_joined_players_public" value="0">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 hover:border-brand-200 hover:bg-brand-50/30 transition-all cursor-pointer">
                        <input type="checkbox" name="show_joined_players_public" id="show_joined_players_public" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                               {{ old('show_joined_players_public', '1') == '1' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-medium text-gray-800">Tampilkan daftar pemain yang sudah join ke publik</span>
                            <span class="block text-xs text-gray-400 mt-0.5">Jika aktif, pengunjung halaman join bisa melihat siapa saja yang sudah masuk.</span>
                        </span>
                    </label>

                    <input type="hidden" name="show_joined_player_contacts_public" value="0">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 hover:border-brand-200 hover:bg-brand-50/30 transition-all cursor-pointer">
                        <input type="checkbox" name="show_joined_player_contacts_public" id="show_joined_player_contacts_public" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                               {{ old('show_joined_player_contacts_public') == '1' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-medium text-gray-800">Tampilkan kontak pemain</span>
                            <span class="block text-xs text-gray-400 mt-0.5">Jika nonaktif, hanya nama pemain yang ditampilkan.</span>
                        </span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 bg-gray-50/50 flex items-center justify-end gap-3">
                <a href="{{ route('admin.matches.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-lime-400 hover:bg-lime-500 text-brand-900 font-bold text-sm shadow-lg shadow-lime-400/20 transition-all hover:shadow-xl hover:shadow-lime-400/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Simpan & Generate Link
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const playersToggle = document.getElementById('show_joined_players_public');
        const contactToggle = document.getElementById('show_joined_player_contacts_public');

        function syncToggles() {
            const enabled = !!playersToggle.checked;
            contactToggle.disabled = !enabled;
            if (!enabled) {
                contactToggle.checked = false;
            }
        }

        playersToggle.addEventListener('change', syncToggles);
        syncToggles();
    })();
</script>
@endpush
