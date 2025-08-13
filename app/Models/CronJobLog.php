<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJobLog extends Model
{
    protected $fillable = [
        'job_key',
        'status',
        'ran_at',
    ];

    protected $casts = [
        'ran_at' => 'datetime',
    ];
}