<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalystProfile extends Model
{
    use HasFactory;

    protected $table = 'analyst_profile';

    protected $primaryKey = 'analyst_id';

    protected $fillable = [
        'user_id',
        'full_name',
        'years_of_experience',
        'description',
        'status',
        'skills',
        'average_rating',
    ];

    protected $casts = [
        'skills' => 'array',
        'average_rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
