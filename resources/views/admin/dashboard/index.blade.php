@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan utama aktivitas admin hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.matches.create') }}" class="px-4 py-2 rounded-xl bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600 shadow-lg shadow-brand-500/20">Buat Event</a>
            {{-- <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:border-brand-200">Lihat Notifikasi</a> --}}
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($quickStats as $stat)
            @php
                $tone = $stat['tone'] === 'emerald' ? 'bg-emerald-50 text-emerald-600' : ($stat['tone'] === 'sky' ? 'bg-sky-50 text-sky-600' : ($stat['tone'] === 'amber' ? 'bg-amber-50 text-amber-700' : 'bg-brand-50 text-brand-600'));
            @endphp
            <div class="pro-card p-5 h-full min-h-[132px] flex flex-col justify-between">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stat['value'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $stat['note'] }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl {{ $tone }} flex items-center justify-center">
                        <span class="text-sm font-bold">PS</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="pro-card p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Agenda Hari Ini</h3>
                    <span class="text-xs text-gray-400">Terbaru</span>
                </div>
                <div class="mt-4 space-y-3">
                    @foreach ($tasks as $task)
                        <div class="flex items-center justify-between border border-gray-100 rounded-xl px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $task['title'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $task['status'] }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-600">{{ $task['priority'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pro-card p-6">
                <h3 class="text-base font-semibold text-gray-900">Aktivitas Terbaru</h3>
                <div class="mt-4 space-y-4">
                    @foreach ($activity as $item)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xs font-bold">EV</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $item['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $item['desc'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $item['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="pro-card p-6">
                <h3 class="text-base font-semibold text-gray-900">Status Event</h3>
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Open</span>
                        <span class="font-semibold text-emerald-600">{{ $statusSummary['open'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Draft</span>
                        <span class="font-semibold text-amber-600">{{ $statusSummary['draft'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Full</span>
                        <span class="font-semibold text-rose-600">{{ $statusSummary['full'] }}</span>
                    </div>
                </div>
            </div>
            <div class="pro-card p-6">
                <h3 class="text-base font-semibold text-gray-900">Reminder</h3>
                <p class="text-sm text-gray-500 mt-2">Kirim pengingat pembayaran sebelum hari Jumat.</p>
                <button class="mt-4 w-full px-4 py-2 rounded-xl bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600">Kirim Reminder</button>
            </div>
        </div>
    </div>
</div>
@endsection
