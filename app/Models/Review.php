<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'reviewer_id',
        'analyst_id',
        'rating',
        'comment',
        'created_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(ClientProfile::class, 'reviewer_id', 'client_id');
    }

    public function analyst()
    {
        return $this->belongsTo(AnalystProfile::class, 'analyst_id', 'analyst_id');
    }
}
