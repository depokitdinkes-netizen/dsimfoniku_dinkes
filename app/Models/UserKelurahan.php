<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKelurahan extends Model
{
    use HasFactory;

    protected $table = 'user_kelurahan';
    
    protected $fillable = [
        'user_id',
        'kelurahan',
        'kecamatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
