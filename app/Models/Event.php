<?php
// app/Models/Event.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event_title', 'category', 'category_other',
        'description', 'venue', 'date_start', 'date_end',
        'publish_status', 'announcement',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end'   => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    // Derive active status from dates
    public function getEventStatusAttribute(): string
    {
        if ($this->publish_status === 'draft') return 'draft';
        $now = now();
        if ($now->lt($this->date_start)) return 'upcoming';
        if ($now->between($this->date_start, $this->date_end)) return 'active';
        return 'completed';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->event_status) {
            'active'    => 'badge-active',
            'upcoming'  => 'badge-upcoming',
            'completed' => 'badge-completed',
            'draft'     => 'badge-draft',
            default     => 'badge-draft',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return $this->category === 'Others'
            ? ($this->category_other ?? 'Others')
            : $this->category;
    }
}
