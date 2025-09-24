<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $primaryKey = 'service_id';

    public $timestamps = false;

    protected $fillable = ['analyst_id', 'title', 'description', 'price_min', 'price_max', 'category'];

    public function analystProfile()
    {
        return $this->belongsTo(AnalystProfile::class, 'analyst_id', 'analyst_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_id', 'service_id');
    }
}
