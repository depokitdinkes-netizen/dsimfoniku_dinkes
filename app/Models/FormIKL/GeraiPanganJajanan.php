<?php

namespace App\Models\FormIKL;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeraiPanganJajanan extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "gerai_pangan_jajanan";
    protected $guarded = ['id'];

    /**
     * Get the user that owns the inspection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
