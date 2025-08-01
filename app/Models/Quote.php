<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quote',
        'audio_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::creating(function ($quote) {
            $quote->uuid = Str::uuid();
        });
    }
}
