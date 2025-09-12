<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SumurGali extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "sumur_gali";
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
