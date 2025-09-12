<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasar extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'pasar';
    protected $guarded = ['id'];
    
    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'tanggal-penilaian' => 'date',
        'total-pedagang' => 'integer',
        'sampel-pedagang' => 'integer',
        'skor' => 'integer',
    ];
    
    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
