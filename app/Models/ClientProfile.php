<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
    use HasFactory;

    protected $table = 'client_profile';

    protected $primaryKey = 'client_id';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'type',
        'company_name',
        'industry',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id', 'client_id');
    }
}
