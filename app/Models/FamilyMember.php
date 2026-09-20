<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'nik',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'relationship',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
