<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kontak',
    ];

    public function communityEvents()
    {
        return $this->belongsToMany(CommunityEvent::class, 'event_participant')->withPivot(
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
