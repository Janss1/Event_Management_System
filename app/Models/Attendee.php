<?php
// app/Models/Attendee.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'first_name', 'middle_name', 'last_name',
        'contact_number', 'email', 'ticket_code', 'checked_in', 'checked_in_at',
    ];

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? " {$this->middle_name}" : '';
        return "{$this->first_name}{$middle} {$this->last_name}";
    }

    protected $casts = [
        'checked_in'    => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($attendee) {
            if (empty($attendee->ticket_code)) {
                $attendee->ticket_code = strtoupper(Str::random(10));
            }
        });
    }
}