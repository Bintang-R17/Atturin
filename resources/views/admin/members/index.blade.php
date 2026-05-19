@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Members</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas pemain berdasarkan riwayat join dan pembayaran.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Total Member</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $summary['total_members'] }}</p>
        </div>
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Member Aktif</p>
            <p class="text-2xl font-bold text-brand-500 mt-1">{{ $summary['active_members'] }}</p>
        </div>
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Total Pembayaran</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($summary['total_paid_amount'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="pro-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Daftar Member</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Join Match</th>
                        <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Paid Match</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Total Bayar</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Terakhir Join</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $member->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->kontak }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">{{ (int) $member->joined_matches }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-emerald-600 font-semibold">{{ (int) $member->paid_matches }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-800">Rp {{ number_format((float) $member->total_paid_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                {{ $member->last_joined_at ? \Carbon\Carbon::parse($member->last_joined_at)->diffForHumans() : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada member.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
