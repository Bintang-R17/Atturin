<?php

namespace App\Http\Controllers;

use App\Models\CommunityEvent;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PlayerJoinController extends Controller
{
    public function show(string $slug)
    {
        $match = CommunityEvent::where('slug', $slug)->firstOrFail();

        $joinedCount = $match->participants()->wherePivot('status_join', 'joined')->count();
        $isFull = $joinedCount >= $match->slot_max;

        $joinedPlayers = collect();
        if ($match->show_joined_players_public) {
            $joinedPlayers = $match->participants()
                ->wherePivot('status_join', 'joined')
                ->orderBy('event_participant.created_at', 'asc')
                ->get(['participants.id', 'participants.nama', 'participants.kontak']);
        }

        return view('player.join.show', compact('match', 'isFull', 'joinedCount', 'joinedPlayers'));
    }

    public function store(Request $request, string $slug)
    {
        $match = CommunityEvent::where('slug', $slug)->firstOrFail();

        $joinedCount = $match->participants()->wherePivot('status_join', 'joined')->count();
        if ($joinedCount >= $match->slot_max) {
            return back()->with('error', 'Maaf, slot pertandingan sudah penuh.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
        ]);

        // Cek jika player dengan kontak yang sama sudah join di match ini
        $existingPlayer = $match->participants()->where('kontak', $validated['kontak'])->first();
        if ($existingPlayer && $existingPlayer->pivot->status_join == 'joined') {
            return back()->with('error', 'Anda sudah bergabung pada pertandingan ini.');
        }

        // Cari atau buat player baru menggunakan data base kontak
        $player = Participant::firstOrCreate(
            ['kontak' => $validated['kontak']],
            ['nama' => $validated['nama']]
        );

        // Jika player sudah pernah join dan batal, update statusnya menjadi joined, jika belum attach baru
        $paymentPayload = [
            'status_join' => 'joined',
            'hadir' => false,
            'payment_method' => $match->metode_pembayaran,
            'payment_amount' => $match->iuran_per_pemain,
            'payment_status' => 'pending',
            'payment_reference' => null,
            'payment_paid_at' => null,
        ];

        if ($existingPlayer) {
            $match->participants()->updateExistingPivot($player->id, $paymentPayload);
        } else {
            $match->participants()->attach($player->id, $paymentPayload);
        }

        session([
            'join_context' => [
                'match_id' => $match->id,
                'player_id' => $player->id,
            ],
        ]);

        return redirect()->route('player.join.success', $match->slug);
    }

    public function success(string $slug)
    {
        $match = CommunityEvent::where('slug', $slug)->firstOrFail();

        $joinContext = session('join_context');
        $latestJoin = null;

        if (
            is_array($joinContext)
            && isset($joinContext['match_id'], $joinContext['player_id'])
            && (int) $joinContext['match_id'] === (int) $match->id
        ) {
            $latestJoin = $match->participants()->where('participants.id', $joinContext['player_id'])->first();
        }

        return view('player.join.success', compact('match', 'latestJoin'));
    }

    public function simulateOnlinePayment($slug)
    {
        $match = CommunityEvent::where('slug', $slug)->firstOrFail();
        $joinContext = session('join_context');

        if (
            !is_array($joinContext)
            || !isset($joinContext['match_id'], $joinContext['player_id'])
            || (int) $joinContext['match_id'] !== (int) $match->id
        ) {
            return redirect()->route('player.join.show', $match->slug)
                ->with('error', 'Sesi pembayaran tidak ditemukan. Silakan join ulang.');
        }

        $player = $match->participants()->where('participants.id', $joinContext['player_id'])->first();
        if (!$player) {
            return redirect()->route('player.join.show', $match->slug)
                ->with('error', 'Data pemain tidak ditemukan. Silakan join ulang.');
        }

        if ($player->pivot->payment_method !== 'online_banking') {
            return back()->with('error', 'Metode pembayaran untuk match ini bukan online banking.');
        }

        if ($player->pivot->payment_status === 'paid') {
            return back()->with('success', 'Pembayaran Anda sudah tercatat sebagai PAID.');
        }

        $reference = 'SIM-MIDTRANS-' . strtoupper(Str::random(10));

        $match->participants()->updateExistingPivot($player->id, [
            'payment_status' => 'paid',
            'payment_reference' => $reference,
            'payment_paid_at' => now(),
        ]);

        return back()->with('success', 'Simulasi pembayaran online berhasil. Status pembayaran Anda sudah PAID.');
    }

    public function midtransToken(Request $request, $slug)
    {
        $match = CommunityEvent::where('slug', $slug)->firstOrFail();
        $joinContext = session('join_context');

        if (
            !is_array($joinContext)
            || !isset($joinContext['match_id'], $joinContext['player_id'])
            || (int) $joinContext['match_id'] !== (int) $match->id
        ) {
            return response()->json(['message' => 'Sesi pembayaran tidak ditemukan.'], 422);
        }

        $player = $match->participants()->where('participants.id', $joinContext['player_id'])->first();
        if (!$player) {
            return response()->json(['message' => 'Data pemain tidak ditemukan.'], 404);
        }

        if ($player->pivot->payment_method !== 'online_banking') {
            return response()->json(['message' => 'Metode pembayaran bukan online banking.'], 422);
        }

        if ($player->pivot->payment_status === 'paid') {
            return response()->json(['message' => 'Pembayaran sudah tercatat.'], 200);
        }

        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');
        if (!$serverKey || !$clientKey) {
            return response()->json(['message' => 'Midtrans key belum dikonfigurasi.'], 500);
        }

        MidtransConfig::$serverKey = $serverKey;
        MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production', false);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;

        $orderId = 'PSH-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
        $grossAmount = (int) $match->iuran_per_pemain;

        $match->participants()->updateExistingPivot($player->id, [
            'payment_reference' => $orderId,
            'payment_status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => [
                [
                    'id' => (string) $match->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => $match->nama_event,
                ],
            ],
            'customer_details' => [
                'first_name' => $player->nama,
                'phone' => $player->kontak,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['token' => $snapToken]);
    }

    public function midtransFinish(Request $request, $slug)
    {
        $match = CommunityEvent::where('slug', $slug)->firstOrFail();
        $joinContext = session('join_context');

        if (
            !is_array($joinContext)
            || !isset($joinContext['match_id'], $joinContext['player_id'])
            || (int) $joinContext['match_id'] !== (int) $match->id
        ) {
            return response()->json(['message' => 'Sesi pembayaran tidak ditemukan.'], 422);
        }

        $player = $match->participants()->where('participants.id', $joinContext['player_id'])->first();
        if (!$player) {
            return response()->json(['message' => 'Data pemain tidak ditemukan.'], 404);
        }

        $transactionStatus = (string) $request->input('transaction_status', 'pending');
        $orderId = $request->input('order_id', $player->pivot->payment_reference);

        $newStatus = match ($transactionStatus) {
            'capture', 'settlement' => 'paid',
            'deny', 'cancel', 'expire' => 'failed',
            default => 'pending',
        };

        $match->participants()->updateExistingPivot($player->id, [
            'payment_status' => $newStatus,
            'payment_reference' => $orderId,
            'payment_paid_at' => $newStatus === 'paid' ? now() : null,
        ]);

        return response()->json(['status' => $newStatus]);
    }
}
