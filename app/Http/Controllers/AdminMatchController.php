<?php

namespace App\Http\Controllers;

use App\Models\CommunityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminMatchController extends Controller
{
    public function index()
    {
        $matches = CommunityEvent::withCount([
            'participants as joined_count' => function ($query) {
                $query->where('event_participant.status_join', 'joined');
            },
            'participants as paid_count' => function ($query) {
                $query->where('event_participant.payment_status', 'paid');
            },
        ])->latest('tanggal')->latest('waktu')->get();

        $recentTransactions = DB::table('event_participant as mp')
            ->join('participants as p', 'p.id', '=', 'mp.participant_id')
            ->join('community_events as m', 'm.id', '=', 'mp.community_event_id')
            ->select(
                'm.id as match_id',
                'm.nama_event',
                'p.nama as player_name',
                'p.kontak as player_contact',
                'mp.payment_amount',
                'mp.payment_status',
                'mp.created_at as joined_at'
            )
            ->where('mp.status_join', 'joined')
            ->orderByDesc('mp.created_at')
            ->limit(10)
            ->get();

        $trendStartDate = now()->startOfDay()->subDays(6);
        $trendByDay = DB::table('event_participant')
            ->selectRaw('DATE(created_at) as joined_date, COUNT(*) as total')
            ->where('status_join', 'joined')
            ->where('created_at', '>=', $trendStartDate)
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'joined_date');

        $trendDays = [];
        $trendCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $trendStartDate->copy()->addDays($i);
            $dateKey = $day->toDateString();
            $trendDays[] = $day->translatedFormat('D');
            $trendCounts[] = (int) ($trendByDay[$dateKey] ?? 0);
        }

        $maxTrend = max(1, max($trendCounts));
        $trendHeights = collect($trendCounts)
            ->map(function ($value) use ($maxTrend) {
                if ($value === 0) {
                    return 8;
                }

                return max(12, (int) round(($value / $maxTrend) * 100));
            })
            ->all();

        return view('admin.matches.index', compact('matches', 'recentTransactions', 'trendDays', 'trendCounts', 'trendHeights'));
    }

    public function members()
    {
        $members = DB::table('participants as p')
            ->leftJoin('event_participant as mp', 'mp.participant_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nama',
                'p.kontak',
                DB::raw("SUM(CASE WHEN mp.status_join = 'joined' THEN 1 ELSE 0 END) as joined_matches"),
                DB::raw("SUM(CASE WHEN mp.payment_status = 'paid' THEN 1 ELSE 0 END) as paid_matches"),
                DB::raw("SUM(CASE WHEN mp.payment_status = 'paid' THEN mp.payment_amount ELSE 0 END) as total_paid_amount"),
                DB::raw('MAX(mp.created_at) as last_joined_at')
            )
            ->groupBy('p.id', 'p.nama', 'p.kontak')
            ->orderByDesc('joined_matches')
            ->orderBy('p.nama')
            ->get();

        $summary = [
            'total_members' => $members->count(),
            'active_members' => $members->where('joined_matches', '>', 0)->count(),
            'total_paid_amount' => (float) $members->sum('total_paid_amount'),
        ];

        return view('admin.members.index', compact('members', 'summary'));
    }

    public function finances()
    {
        $financeRows = DB::table('community_events as m')
            ->leftJoin('event_participant as mp', 'mp.community_event_id', '=', 'm.id')
            ->select(
                'm.id',
                'm.nama_event',
                'm.tanggal',
                'm.waktu',
                'm.tempat',
                'm.iuran_per_pemain',
                'm.metode_pembayaran',
                DB::raw("SUM(CASE WHEN mp.status_join = 'joined' THEN 1 ELSE 0 END) as joined_count"),
                DB::raw("SUM(CASE WHEN mp.payment_status = 'paid' THEN 1 ELSE 0 END) as paid_count"),
                DB::raw("SUM(CASE WHEN mp.status_join = 'joined' THEN mp.payment_amount ELSE 0 END) as expected_amount"),
                DB::raw("SUM(CASE WHEN mp.payment_status = 'paid' THEN mp.payment_amount ELSE 0 END) as collected_amount")
            )
            ->groupBy('m.id', 'm.nama_event', 'm.tanggal', 'm.waktu', 'm.tempat', 'm.iuran_per_pemain', 'm.metode_pembayaran')
            ->orderByDesc('m.tanggal')
            ->orderByDesc('m.waktu')
            ->get()
            ->map(function ($row) {
                $row->pending_amount = (float) $row->expected_amount - (float) $row->collected_amount;

                return $row;
            });

        $summary = [
            'total_expected' => (float) $financeRows->sum('expected_amount'),
            'total_collected' => (float) $financeRows->sum('collected_amount'),
            'total_pending' => (float) $financeRows->sum('pending_amount'),
            'total_paid_players' => (int) $financeRows->sum('paid_count'),
        ];

        return view('admin.finances.index', compact('financeRows', 'summary'));
    }

    public function create()
    {
        return view('admin.matches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'tempat' => 'required|string|max:255',
            'link_maps' => 'nullable|url|max:2000',
            'slot_max' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:online_banking,tunai',
            'iuran_per_pemain' => 'required|numeric|min:0',
            'show_joined_players_public' => 'nullable|boolean',
            'show_joined_player_contacts_public' => 'nullable|boolean',
        ]);

        $validated['kategori'] = $validated['kategori'] ?: 'Olahraga';

        $validated['show_joined_players_public'] = $request->boolean('show_joined_players_public', true);
        $validated['show_joined_player_contacts_public'] = $request->boolean('show_joined_player_contacts_public');

        if (!$validated['show_joined_players_public']) {
            $validated['show_joined_player_contacts_public'] = false;
        }

        $validated['slug'] = Str::slug($validated['nama_event'] . '-' . uniqid());

        CommunityEvent::create($validated);

        return redirect()->route('admin.matches.index')->with('success', 'Event berhasil dibuat.');
    }

    public function show(CommunityEvent $match)
    {
        $livePayload = $this->buildLivePayload($match);
        $joinedCount = $livePayload['metrics']['joined_count'];

        return view('admin.matches.show', compact('match', 'joinedCount', 'livePayload'));
    }

    public function live(CommunityEvent $match)
    {
        return response()->json($this->buildLivePayload($match));
    }

    public function updateJoinVisibility(Request $request, CommunityEvent $match)
    {
        $request->validate([
            'show_joined_players_public' => 'nullable|boolean',
            'show_joined_player_contacts_public' => 'nullable|boolean',
        ]);

        $payload = [
            'show_joined_players_public' => $request->boolean('show_joined_players_public'),
            'show_joined_player_contacts_public' => $request->boolean('show_joined_player_contacts_public'),
        ];

        if (!$payload['show_joined_players_public']) {
            $payload['show_joined_player_contacts_public'] = false;
        }

        $match->update($payload);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pengaturan visibilitas join berhasil diupdate.',
                'settings' => $payload,
            ]);
        }

        return back()->with('success', 'Pengaturan visibilitas join berhasil diupdate.');
    }

    public function updateAttendance(Request $request, CommunityEvent $match, int $playerId)
    {
        $match->participants()->updateExistingPivot($playerId, [
            'hadir' => $request->has('hadir') ? true : false
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status kehadiran berhasil diupdate.',
                'live' => $this->buildLivePayload($match),
            ]);
        }

        return back()->with('success', 'Status kehadiran berhasil diupdate.');
    }

    public function updateStatus(Request $request, CommunityEvent $match, int $playerId)
    {
        $validated = $request->validate([
            'status_join' => 'required|in:joined,batal'
        ]);

        $match->participants()->updateExistingPivot($playerId, [
            'status_join' => $validated['status_join']
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status join berhasil diupdate.',
                'live' => $this->buildLivePayload($match),
            ]);
        }

        return back()->with('success', 'Status join berhasil diupdate.');
    }

    public function updatePayment(Request $request, CommunityEvent $match, int $playerId)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $player = $match->participants()->where('participants.id', $playerId)->first();
        if (!$player) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Pemain tidak ditemukan pada match ini.',
                ], 404);
            }

            return back()->with('error', 'Pemain tidak ditemukan pada match ini.');
        }

        $payload = [
            'payment_status' => $validated['payment_status'],
        ];

        if ($validated['payment_status'] === 'paid') {
            $payload['payment_paid_at'] = now();
            if (empty($player->pivot->payment_reference)) {
                $prefix = $player->pivot->payment_method === 'online_banking' ? 'ADMIN-MB' : 'ADMIN-CASH';
                $payload['payment_reference'] = $prefix . '-' . strtoupper(Str::random(8));
            }
        } else {
            $payload['payment_paid_at'] = null;
        }

        $match->participants()->updateExistingPivot($playerId, $payload);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status pembayaran berhasil diupdate.',
                'live' => $this->buildLivePayload($match),
            ]);
        }

        return back()->with('success', 'Status pembayaran berhasil diupdate.');
    }

    private function buildLivePayload(CommunityEvent $match): array
    {
        $match->load(['participants' => function ($query) {
            $query->orderBy('event_participant.created_at', 'asc');
        }]);

        $players = $match->participants->map(function ($player) {
            $joinedAt = $player->pivot->created_at;

            return [
                'id' => $player->id,
                'nama' => $player->nama,
                'kontak' => $player->kontak,
                'joined_at_human' => $joinedAt ? $joinedAt->diffForHumans() : '-',
                'status_join' => $player->pivot->status_join,
                'hadir' => (bool) $player->pivot->hadir,
                'payment_method' => $player->pivot->payment_method,
                'payment_method_label' => $player->pivot->payment_method === 'online_banking' ? 'Online Banking' : 'Tunai',
                'payment_amount' => (float) $player->pivot->payment_amount,
                'payment_status' => $player->pivot->payment_status,
                'payment_reference' => $player->pivot->payment_reference,
            ];
        })->values();

        $joinedCount = $players->where('status_join', 'joined')->count();
        $paidCount = $players->where('payment_status', 'paid')->count();
        $pendingCount = $players->where('payment_status', 'pending')->count();
        $failedCount = $players->where('payment_status', 'failed')->count();
        $totalCollected = $players
            ->where('payment_status', 'paid')
            ->sum(function ($player) {
                return (float) $player['payment_amount'];
            });

        return [
            'metrics' => [
                'joined_count' => $joinedCount,
                'slot_max' => $match->slot_max,
                'paid_count' => $paidCount,
                'pending_count' => $pendingCount,
                'failed_count' => $failedCount,
                'total_collected' => $totalCollected,
            ],
            'settings' => [
                'show_joined_players_public' => (bool) $match->show_joined_players_public,
                'show_joined_player_contacts_public' => (bool) $match->show_joined_player_contacts_public,
            ],
            'players' => $players,
            'updated_at' => now()->toDateTimeString(),
        ];
    }
}
