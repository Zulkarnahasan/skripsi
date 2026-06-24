<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $fillable = ['user_id', 'student_profile_id', 'registration_number', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function scores()
    {
        return $this->hasMany(AlternativeScore::class);
    }

    public function testAnswers()
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function sawResult()
    {
        return $this->hasOne(SawResult::class);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        return $query->when($keyword, fn ($q) => $q->where('registration_number', 'like', "%{$keyword}%")
            ->orWhereHas('studentProfile', fn ($profile) => $profile
                ->where('nisn', 'like', "%{$keyword}%")
                ->orWhere('npsn', 'like', "%{$keyword}%")
                ->orWhere('kip_account_number', 'like', "%{$keyword}%")
                ->orWhere('phone', 'like', "%{$keyword}%"))
            ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%")));
    }
}
