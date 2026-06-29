<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualAssessmentScore extends Model
{
    protected $fillable = ['alternative_id', 'criteria_id', 'component_key', 'component_name', 'score'];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:4',
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
