<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBrief extends Model
{
    use HasFactory;

    protected $primaryKey = 'brief_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'project_description',
        'attachments_url',
        'submitted_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
