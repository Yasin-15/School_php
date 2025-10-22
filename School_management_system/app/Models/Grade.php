<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'marks_obtained',
        'grade_letter',
        'grade_points',
        'remarks',
        'is_absent',
    ];

    protected function casts(): array
    {
        return [
            'marks_obtained' => 'decimal:2',
            'grade_points' => 'decimal:2',
            'is_absent' => 'boolean',
        ];
    }

    /**
     * Get the student that owns the grade.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the exam that owns the grade.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Calculate grade letter based on marks
     */
    public function calculateGradeLetter(): string
    {
        $percentage = ($this->marks_obtained / $this->exam->total_marks) * 100;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        return 'F';
    }

    /**
     * Check if student passed
     */
    public function isPassed(): bool
    {
        return $this->marks_obtained >= $this->exam->passing_marks;
    }
}