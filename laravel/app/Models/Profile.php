<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'school_name',
        'npsn',
        'address',
        'phone',
        'email',
        'website',
        'principal_name',
        'principal_nip',
        'academic_year',
        'semester',
        'curriculum',
        'logo_url',
    ];
}
