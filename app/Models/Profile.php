<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id', 'category_id', 'timezone', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function weeklyAvailabilities()
    {
        return $this->hasMany(WeeklyAvailability::class);
    }

    public function dateOverrides()
    {
        return $this->hasMany(DateOverride::class);
    }
}
