@extends('layouts.admin')

@section('content')
@php
    $eventCards = [
        ['title' => 'Liga Jumat Malam', 'date' => 'Jumat, 19:30', 'location' => 'Arena Harmoni', 'status' => 'Open', 'slots' => '12/20', 'tag' => 'Community'],
        ['title' => 'Mini Cup U23', 'date' => 'Sabtu, 15:00', 'location' => 'SportHub', 'status' => 'Ready', 'slots' => '8/16', 'tag' => 'Tournament'],
        ['title' => 'Friendly Match', 'date' => 'Minggu, 09:00', 'location' => 'Stadium 7', 'status' => 'Full', 'slots' => '20/20', 'tag' => 'Weekly'],
    ];

    $upcoming = [
        ['name' => 'Liga Malam 5v5', 'time' => '20:00', 'day' => 'Tue', 'city' => 'Jakarta', 'progress' => 72],
        ['name' => 'Corporate League', 'time' => '18:30', 'day' => 'Thu', 'city' => 'Bandung', 'progress' => 45],
        ['name' => 'Coach Clinic', 'time' => '10:00', 'day' => 'Sat', 'city' => 'Bogor', 'progress' => 90],
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Events Board</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau daftar event dan status pendaftaran terbaru.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:border-brand-200 hover:text-brand-600">Import Data</button>
            <a href="{{ route('admin.matches.create') }}" class="px-4 py-2 rounded-xl bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600 shadow-lg shadow-brand-500/20">Buat Event</a>
        </div>
    </div>

    <div class="pro-card p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Cari Event</label>
                <input type="text" placeholder="Cari nama event" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-200">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</label>
                <select class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-200">
                    <option>Semua</option>
                    <option>Tournament</option>
                    <option>Weekly</option>
                    <option>Training</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</label>
                <select class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-200">
                    <option>Semua</option>
                    <option>Open</option>
                    <option>Ready</option>
                    <option>Full</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Range</label>
                <input type="text" placeholder="05 Mei - 12 Mei" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-200">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-4">
            @foreach ($eventCards as $event)
                @php
                    $statusColor = $event['status'] === 'Full' ? 'bg-rose-100 text-rose-700' : ($event['status'] === 'Ready' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                @endphp
                <div class="pro-card p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $event['title'] }}</h3>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusColor }}">{{ $event['status'] }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $event['date'] }} • {{ $event['location'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Tag: {{ $event['tag'] }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Slot</p>
                            <p class="text-lg font-bold text-gray-900">{{ $event['slots'] }}</p>
                        </div>
                        <a href="{{ route('admin.matches.index') }}" class="px-4 py-2 rounded-xl bg-brand-50 text-brand-600 text-sm font-semibold hover:bg-brand-100">Kelola</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-4">
            <div class="pro-card p-5">
                <h3 class="text-base font-semibold text-gray-900">Upcoming Highlights</h3>
                <div class="mt-4 space-y-4">
                    @foreach ($upcoming as $item)
                        <div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $item['day'] }} • {{ $item['time'] }} • {{ $item['city'] }}</p>
                                </div>
                                <span class="text-xs font-semibold text-brand-600">{{ $item['progress'] }}%</span>
                            </div>
                            <div class="mt-2 w-full h-2 rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-brand-500" style="width: {{ $item['progress'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="pro-card p-5">
                <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
                <div class="mt-4 space-y-3">
                    <button class="w-full px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:border-brand-200">Publish Drafts</button>
                    <button class="w-full px-4 py-2 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:border-brand-200">Sync Calendar</button>
                    <button class="w-full px-4 py-2 rounded-xl bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600">Send Reminder</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
