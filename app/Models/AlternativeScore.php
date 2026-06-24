<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlternativeScore extends Model
{
    protected $fillable = ['alternative_id', 'criteria_id', 'raw_value', 'score', 'normalized_value', 'weighted_value'];

    protected function casts(): array
    {
        return [
            'raw_value' => 'decimal:4',
            'score' => 'decimal:4',
            'normalized_value' => 'decimal:6',
            'weighted_value' => 'decimal:6',
        ];
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
