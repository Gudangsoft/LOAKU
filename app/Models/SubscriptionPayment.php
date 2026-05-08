<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'publisher_id',
        'subscription_plan_id',
        'invoice_number',
        'amount',
        'payment_proof',
        'status',
        'admin_notes',
        'confirmed_by',
        'confirmed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public static function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('invoice_number', $number)->exists());

        return $number;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_payment'  => 'Menunggu Pembayaran',
            'proof_uploaded'   => 'Bukti Dikirim',
            'confirmed'        => 'Dikonfirmasi',
            'rejected'         => 'Ditolak',
            default            => $this->status,
        };
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'pending_payment'  => 'warning',
            'proof_uploaded'   => 'info',
            'confirmed'        => 'success',
            'rejected'         => 'danger',
            default            => 'secondary',
        };
    }

    public function isPending(): bool  { return $this->status === 'pending_payment'; }
    public function isUploaded(): bool { return $this->status === 'proof_uploaded'; }
    public function isConfirmed(): bool{ return $this->status === 'confirmed'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
