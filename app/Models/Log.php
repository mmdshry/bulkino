<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];
    protected $casts = [
        'request' => 'json',
        'response' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
