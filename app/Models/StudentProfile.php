<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'kip_account_number',
        'school_origin',
        'entry_year',
        'nisn',
        'npsn',
        'address',
        'phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'entry_year' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function alternative()
    {
        return $this->hasOne(Alternative::class);
    }
}
