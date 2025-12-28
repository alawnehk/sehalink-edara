<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorAvailability extends Model
{
    protected $fillable = ['user_id', 'day_of_week', 'start_time', 'end_time', 'is_active'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}