<?php

namespace App\Models;

use App\Traits\HasSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'send_date',
        'timezone',
        'status',
        'author',
        'tags',
        'sponsors',
        'metadata',
        'hash',
        'total_recipients',
        'sent_count',
        'failed_count',
        'sent_at',
    ];

    protected $casts = [
        'send_date' => 'datetime',
        'sent_at' => 'datetime',
        'tags' => 'array',
        'sponsors' => 'array',
        'metadata' => 'array',
    ];

    public function isReadyToSend(): bool
    {
        return $this->status === 'scheduled' &&
               $this->send_date <= now($this->timezone);
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsSending(): void
    {
        $this->update(['status' => 'sending']);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    protected function sendDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value, $this->timezone) : null,
        );
    }

    public function getSlugSourceAttribute(): string
    {
        return $this->title;
    }
}
