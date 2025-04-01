<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'deliveries';
    protected $fillable = [
        'id',
        'invoice_id',
        'worker_id',
        'delivery_date',
        'carrier',
        'status',
    ];
    protected $casts = [
        'delivery_date' => 'date',
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
