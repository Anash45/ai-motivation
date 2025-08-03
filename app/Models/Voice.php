<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'model_id',
    ];

    /**
     * Get the users that use this voice.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}