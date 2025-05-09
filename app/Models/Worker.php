<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'RFID',
        'RFC',
        'NSS',
        'person_id',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'assigned_to','worker_id'); 
    }
}
