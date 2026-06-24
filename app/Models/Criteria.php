<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = ['code', 'name', 'type', 'weight', 'description'];

    protected function casts(): array
    {
        return ['weight' => 'decimal:4'];
    }

    public function subCriteria()
    {
        return $this->hasMany(SubCriteria::class);
    }

    public function scores()
    {
        return $this->hasMany(AlternativeScore::class);
    }

    public function testQuestions()
    {
        return $this->hasMany(TestQuestion::class);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        return $query->when($keyword, fn ($q) => $q->where('code', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%"));
    }
}
