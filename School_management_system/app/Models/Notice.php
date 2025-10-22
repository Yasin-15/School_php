<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'target_audience',
        'priority',
        'publish_date',
        'expiry_date',
        'attachment',
        'is_published',
        'is_urgent',
    ];

    protected function casts(): array
    {
        return [
            'publish_date' => 'datetime',
            'expiry_date' => 'datetime',
            'is_published' => 'boolean',
            'is_urgent' => 'boolean',
        ];
    }

    /**
     * Get the user who created the notice.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if notice is active
     */
    public function isActive(): bool
    {
        return $this->is_published && 
               $this->publish_date <= now() && 
               ($this->expiry_date === null || $this->expiry_date >= now());
    }

    /**
     * Check if notice is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date !== null && $this->expiry_date < now();
    }
}