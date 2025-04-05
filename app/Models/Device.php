<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'area_id',
        'password',
        'reading_time',
        'response_time',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
