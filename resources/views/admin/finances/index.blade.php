@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Finances</h1>
        <p class="text-sm text-gray-500 mt-1">Monitoring target iuran, pembayaran terkumpul, dan outstanding per event.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Target Iuran</p>
            <p class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($summary['total_expected'], 0, ',', '.') }}</p>
        </div>
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Terkumpul</p>
            <p class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($summary['total_collected'], 0, ',', '.') }}</p>
        </div>
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Outstanding</p>
            <p class="text-xl font-bold text-amber-600 mt-1">Rp {{ number_format($summary['total_pending'], 0, ',', '.') }}</p>
        </div>
        <div class="pro-card p-5">
            <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">Paid Players</p>
            <p class="text-xl font-bold text-brand-500 mt-1">{{ $summary['total_paid_players'] }}</p>
        </div>
    </div>

    <div class="pro-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Laporan per Event</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Joined/Paid</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Collected</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Outstanding</th>
                        <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($financeRows as $row)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-800">{{ $row->nama_event }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }} • {{ $row->waktu }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $row->metode_pembayaran === 'online_banking' ? 'Online Banking' : 'Tunai' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                {{ (int) $row->joined_count }} / {{ (int) $row->paid_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-800">
                                Rp {{ number_format((float) $row->expected_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-emerald-600">
                                Rp {{ number_format((float) $row->collected_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold {{ (float) $row->pending_amount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                Rp {{ number_format((float) $row->pending_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.matches.show', $row->id) }}" class="inline-flex items-center gap-1 text-sm font-medium text-brand-500 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada data finansial.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
