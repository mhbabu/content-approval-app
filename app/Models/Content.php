<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'media_path',
        'thumbnail_path',
        'status',
        'published_at',
        'is_uploaded',
        'rejected_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
