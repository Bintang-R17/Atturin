@extends('layouts.admin')

@section('content')
@php
    $totalMatches = $matches->count();
    $totalJoined = $matches->sum('joined_count');
    $totalPaid = $matches->sum('paid_count');
    $totalPotentialRevenue = $matches->sum(function ($item) {
        return (float) $item->iuran_per_pemain * (int) $item->joined_count;
    });
    $totalSlotMax = $matches->sum('slot_max');
    $totalSlotAvailable = $totalSlotMax - $totalJoined;
    $fillPercentage = $totalSlotMax > 0 ? round(($totalJoined / $totalSlotMax) * 100) : 0;
@endphp

<div class="space-y-6">
    {{-- Greeting --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Halo, Admin!</h1>
        <p class="text-sm text-gray-500 mt-1">Berikut adalah ringkasan performa ProSports Hub hari ini.</p>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Total Pendapatan --}}
        <div class="pro-card p-5">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalPotentialRevenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        dari iuran terkumpul
                    </p>
                </div>
            </div>
        </div>

        {{-- Event Aktif --}}
        <div class="pro-card p-5">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Event Aktif</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $totalMatches }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $totalMatches }} event minggu ini</p>
                </div>
            </div>
        </div>

        {{-- Total Peserta --}}
        <div class="pro-card p-5">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Total Peserta</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $totalJoined }}</p>
                    <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        {{ $totalPaid }} sudah bayar
                    </p>
                </div>
            </div>
        </div>

        {{-- Slot Tersedia --}}
        <div class="pro-card p-5">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-lime-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Slot Tersedia</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $totalSlotAvailable }}</p>
                    <div class="mt-2">
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full bg-lime-400 transition-all duration-500" style="width: {{ $fillPercentage }}%"></div>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">{{ $fillPercentage }}% Terisi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Grid: Chart + Upcoming Events --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Registration Trend --}}
        <div class="xl:col-span-2 pro-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-gray-900">Tren Pendaftaran</h3>
                <a href="{{ route('admin.members.index') }}" class="text-sm text-brand-500 hover:text-brand-600 font-medium flex items-center gap-1">
                    Lihat Detail
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            {{-- CSS Bar Chart --}}
            <div class="flex items-end gap-3 h-48">
                @forelse($trendDays as $i => $day)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full rounded-t-lg transition-all duration-300 hover:opacity-80 {{ $trendHeights[$i] >= 50 ? 'bg-brand-500' : 'bg-brand-100' }}"
                             style="height: {{ $trendHeights[$i] }}%"
                             title="{{ $trendCounts[$i] }} pendaftar"></div>
                        <span class="text-xs text-gray-400 font-medium">{{ $day }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data pendaftaran.</p>
                @endforelse
            </div>
        </div>

        {{-- Transaksi Terbaru (Compact) --}}
        <div class="pro-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900">Transaksi Terbaru</h3>
            </div>
            <div class="space-y-4">
                @forelse ($recentTransactions->take(5) as $transaction)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-600 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($transaction->player_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $transaction->player_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $transaction->nama_event }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold {{ $transaction->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                Rp {{ number_format((float) $transaction->payment_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($transaction->joined_at)->diffForHumans(null, true, true) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Daftar Event (Full Width Table) --}}
    <div class="pro-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-900">Daftar Semua Event</h3>
            <a href="{{ route('admin.matches.create') }}" class="text-sm text-brand-500 font-medium hover:text-brand-600">+ Buat Event</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Nama Event & Venue</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Slot Terisi</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Keuangan</th>
                        <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($matches as $match)
                        @php
                            $slotsLeft = $match->slot_max - $match->joined_count;
                            $matchDate = \Carbon\Carbon::parse($match->tanggal);
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">{{ $matchDate->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($match->waktu)->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-brand-600">{{ $match->nama_event }}</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $match->tempat }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-800">{{ $match->joined_count }}/{{ $match->slot_max }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $slotsLeft <= 0 ? 'bg-rose-100 text-rose-700' : ($slotsLeft <= 5 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ $slotsLeft <= 0 ? 'PENUH' : $slotsLeft . ' SISA' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-emerald-600">Rp {{ number_format((float) ($match->iuran_per_pemain * $match->paid_count), 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wide">{{ $match->paid_count }} Lunas</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.matches.show', $match->id) }}"
                                   class="inline-flex items-center gap-1 text-sm font-medium text-white bg-brand-500 hover:bg-brand-600 px-4 py-2 rounded-xl transition-all shadow-lg shadow-brand-500/20">
                                    Detail & Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-sm text-gray-400">Belum ada match yang dibuat.</p>
                                    <a href="{{ route('admin.matches.create') }}" class="text-sm text-brand-500 font-medium hover:text-brand-600">+ Buat Event Pertama</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
