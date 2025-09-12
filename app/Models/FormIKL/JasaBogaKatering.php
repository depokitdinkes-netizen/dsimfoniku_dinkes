<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JasaBogaKatering extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "jasa_boga_katering";
    protected $guarded = ['id'];
    
    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
