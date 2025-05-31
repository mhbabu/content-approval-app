<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyAvailability extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'day_of_week', 'start_time', 'end_time'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
