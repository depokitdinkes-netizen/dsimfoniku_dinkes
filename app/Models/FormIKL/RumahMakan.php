<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RumahMakan extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "rumah_makan";
    protected $guarded = ['id'];
    
    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
