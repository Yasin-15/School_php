<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_type',
        'amount',
        'due_date',
        'paid_amount',
        'paid_date',
        'payment_method',
        'transaction_id',
        'status',
        'academic_year',
        'month',
        'late_fee',
        'discount',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_date' => 'date',
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'discount' => 'decimal:2',
        ];
    }

    /**
     * Get the student that owns the fee.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if fee is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if fee is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date < now() && $this->status !== 'paid';
    }

    /**
     * Get remaining amount
     */
    public function getRemainingAmount(): float
    {
        return $this->amount - $this->paid_amount - $this->discount + $this->late_fee;
    }
}