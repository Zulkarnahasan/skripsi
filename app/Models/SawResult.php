<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SawResult extends Model
{
    protected $fillable = ['alternative_id', 'final_score', 'rank', 'status', 'announced_at'];

    protected function casts(): array
    {
        return ['final_score' => 'decimal:6', 'announced_at' => 'datetime'];
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}
