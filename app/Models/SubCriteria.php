<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    protected $table = 'sub_criteria';

    protected $fillable = ['criteria_id', 'name', 'min_value', 'max_value', 'score'];

    protected function casts(): array
    {
        return ['min_value' => 'decimal:2', 'max_value' => 'decimal:2', 'score' => 'decimal:4'];
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
