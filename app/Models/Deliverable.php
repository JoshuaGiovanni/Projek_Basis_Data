<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    use HasFactory;

    protected $primaryKey = 'deliverable_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'file_url',
        'submitted_at',
        'approved_by_admin',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}



