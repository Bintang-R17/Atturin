<?php

namespace App\Http\Controllers;

use App\Models\CommunityEvent;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $events = CommunityEvent::withCount([
            'participants as joined_count' => function ($query) {
                $query->where('event_participant.status_join', 'joined');
            },
            'participants as paid_count' => function ($query) {
                $query->where('event_participant.payment_status', 'paid');
            },
        ])->latest('tanggal')->latest('waktu')->get();

        $totalEvents = $events->count();
        $totalJoined = $events->sum('joined_count');
        $totalPaid = $events->sum('paid_count');
        $totalRevenue = $events->sum(function ($event) {
            return (float) $event->iuran_per_pemain * (int) $event->paid_count;
        });
        $totalSlots = $events->sum('slot_max');
        $slotsAvailable = $totalSlots - $totalJoined;

        $quickStats = [
            ['label' => 'Total Event', 'value' => (string) $totalEvents, 'note' => $totalEvents . ' aktif', 'tone' => 'brand'],
            ['label' => 'Peserta Minggu Ini', 'value' => (string) $totalJoined, 'note' => $totalPaid . ' sudah bayar', 'tone' => 'emerald'],
            ['label' => 'Pembayaran Terkonfirmasi', 'value' => (string) $totalPaid, 'note' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'tone' => 'sky'],
            ['label' => 'Slot Tersisa', 'value' => (string) max(0, $slotsAvailable), 'note' => $totalSlots > 0 ? round(($totalJoined / $totalSlots) * 100) . '% terisi' : 'Belum ada slot', 'tone' => 'amber'],
        ];

        $tasks = $events
            ->map(function ($event) {
                $slotsLeft = (int) $event->slot_max - (int) $event->joined_count;
                $priority = $slotsLeft <= 3 ? 'High' : 'Medium';
                $status = $slotsLeft <= 0 ? 'Penuh' : $slotsLeft . ' slot tersisa';

                return [
                    'title' => 'Review ' . $event->nama_event,
                    'status' => $status,
                    'priority' => $priority,
                ];
            })
            ->take(3)
            ->values()
            ->all();

        $activity = DB::table('event_participant as ep')
            ->join('community_events as e', 'e.id', '=', 'ep.community_event_id')
            ->select('e.nama_event', 'ep.payment_status', 'ep.created_at')
            ->orderByDesc('ep.created_at')
            ->limit(3)
            ->get()
            ->map(function ($row) {
                $label = $row->payment_status === 'paid' ? 'Pembayaran diterima' : 'Pendaftaran baru';
                $timeLabel = $row->created_at
                    ? \Carbon\Carbon::parse($row->created_at)->diffForHumans()
                    : 'baru saja';

                return [
                    'title' => $row->nama_event,
                    'desc' => $label,
                    'time' => $timeLabel,
                ];
            })
            ->all();

        if ($totalEvents === 0) {
            $quickStats = [
                ['label' => 'Total Event', 'value' => '0', 'note' => 'Belum ada event', 'tone' => 'brand'],
                ['label' => 'Peserta Minggu Ini', 'value' => '0', 'note' => '0 sudah bayar', 'tone' => 'emerald'],
                ['label' => 'Pembayaran Terkonfirmasi', 'value' => '0', 'note' => 'Rp 0', 'tone' => 'sky'],
                ['label' => 'Slot Tersisa', 'value' => '0', 'note' => 'Belum ada slot', 'tone' => 'amber'],
            ];
            $tasks = [
                ['title' => 'Buat event pertama', 'status' => 'Hari ini', 'priority' => 'High'],
                ['title' => 'Atur metode pembayaran', 'status' => 'Besok', 'priority' => 'Medium'],
                ['title' => 'Kirim info ke member', 'status' => 'Minggu ini', 'priority' => 'Medium'],
            ];
            $activity = [
                ['title' => 'Belum ada aktivitas', 'desc' => 'Mulai dengan membuat event baru.', 'time' => 'baru saja'],
            ];
        }

        $statusSummary = [
            'open' => $events->filter(function ($event) {
                return (int) $event->slot_max - (int) $event->joined_count > 0;
            })->count(),
            'full' => $events->filter(function ($event) {
                return (int) $event->slot_max - (int) $event->joined_count <= 0;
            })->count(),
            'draft' => 0,
        ];

        return view('admin.dashboard.index', compact('quickStats', 'tasks', 'activity', 'statusSummary'));
    }
}
