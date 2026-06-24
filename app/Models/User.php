<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function alternative()
    {
        return $this->hasOne(Alternative::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        return $query->when($keyword, fn ($q) => $q->where(fn ($qq) => $qq
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhereHas('studentProfile', fn ($profile) => $profile
                ->where('nisn', 'like', "%{$keyword}%")
                ->orWhere('npsn', 'like', "%{$keyword}%")
                ->orWhere('kip_account_number', 'like', "%{$keyword}%")
                ->orWhere('phone', 'like', "%{$keyword}%"))));
    }
}
