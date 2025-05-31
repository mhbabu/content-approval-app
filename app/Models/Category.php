<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Profiler\Profile;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'status'];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}
