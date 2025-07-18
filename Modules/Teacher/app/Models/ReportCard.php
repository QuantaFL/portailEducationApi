<?php

namespace Modules\Teacher\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Etudiant\Models\Etudiant;
use Modules\Classes\Models\Classes;

class ReportCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'class_id',
        'period',
        'general_average',
        'mention',
        'rank',
        'appreciation',
        'subject_averages',
    ];

    protected $casts = [
        'subject_averages' => 'array',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
