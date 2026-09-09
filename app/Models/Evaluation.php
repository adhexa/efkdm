<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_code',
        'user_id',
        'title',
        'period_name',
        'target_region',
        'total_target_reports',
        'total_realized_reports',
        'compliance_rate',
        'risk_index_score',
        'executive_summary',
        'consultant_recommendations',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
