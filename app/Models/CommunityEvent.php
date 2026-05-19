<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama_event
 * @property string|null $kategori
 * @property string $tanggal
 * @property string $waktu
 * @property string $tempat
 * @property string|null $link_maps
 * @property int $slot_max
 * @property string $metode_pembayaran
 * @property float $iuran_per_pemain
 */
class CommunityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_event',
        'kategori',
        'tanggal',
        'waktu',
        'tempat',
        'link_maps',
        'slot_max',
        'metode_pembayaran',
        'iuran_per_pemain',
        'show_joined_players_public',
        'show_joined_player_contacts_public',
        'slug',
    ];

    protected $casts = [
        'iuran_per_pemain' => 'decimal:2',
        'show_joined_players_public' => 'boolean',
        'show_joined_player_contacts_public' => 'boolean',
    ];

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'event_participant')->withPivot(
            'status_join',
            'hadir',
            'payment_method',
            'payment_amount',
            'payment_status',
            'payment_reference',
            'payment_paid_at'
        )->withTimestamps();
    }
}
