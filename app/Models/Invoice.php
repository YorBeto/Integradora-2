<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $dates = ['invoice_date'];

    protected $fillable = [
        'id',
        'details',
        'URL',
        'status',
    ];
}
