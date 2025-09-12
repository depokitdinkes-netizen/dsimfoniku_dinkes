<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perpipaan extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "perpipaan";
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
