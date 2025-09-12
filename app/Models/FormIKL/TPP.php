<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPP extends Model {
    use HasFactory;

    protected $table = "tpp_tertentu";
    protected $guarded = ['id'];
}
