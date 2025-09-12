<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasFactory;

    protected $table = "users";
    protected $guarded = ['id'];
    
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'role',
        'kecamatan',
        'kelurahan',
        'baris1',
        'baris2',
        'baris3',
        'baris4',
        'baris5',
        'sizebaris1',
        'sizebaris2',
        'sizebaris3',
        'sizebaris4',
        'sizebaris5'
    ];    /**
     * Relationship to kelurahan (many-to-many)
     */
    public function userKelurahan()
    {
        return $this->hasMany(UserKelurahan::class);
    }

    /**
     * Get all kelurahan for this user
     */
    public function getKelurahanListAttribute()
    {
        return $this->userKelurahan->pluck('kelurahan')->toArray();
    }

    /**
     * Get kecamatan for this user (assuming all kelurahan are in same kecamatan)
     */
    public function getKecamatanFromKelurahanAttribute()
    {
        return $this->userKelurahan->first()?->kecamatan;
    }

    /**
     * Relationship to inspection data
     */
    public function restoran()
    {
        return $this->hasMany(\App\Models\FormIKL\Restoran::class);
    }
    
    public function jasaBogaKatering()
    {
        return $this->hasMany(\App\Models\FormIKL\JasaBogaKatering::class);
    }
    
    public function rumahMakan()
    {
        return $this->hasMany(\App\Models\FormIKL\RumahMakan::class);
    }
    
    // Add other relationships as needed...
}
