<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    protected $fillable = ['alternative_id', 'test_question_id', 'selected_answer', 'score'];

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

    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id');
    }
}
