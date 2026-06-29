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
        'study_program',
        'study_program_accreditation',
        'study_program_2',
        'study_program_accreditation_2',
        'entry_year',
        'graduation_year',
        'nisn',
        'npsn',
        'address',
        'phone',
        'profile_photo_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'entry_year' => 'integer',
            'graduation_year' => 'integer',
        ];
    }

    public function isComplete(): bool
    {
        return collect([
            $this->kip_account_number,
            $this->school_origin,
            $this->study_program,
            $this->study_program_accreditation,
            $this->study_program_2,
            $this->study_program_accreditation_2,
            $this->entry_year,
            $this->graduation_year,
            $this->nisn,
            $this->npsn,
            $this->phone,
            $this->profile_photo_path,
        ])->every(fn ($value) => filled($value));
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
