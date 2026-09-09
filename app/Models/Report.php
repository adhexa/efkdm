<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'user_id',
        'category_id',
        'title',
        'chronology',
        'risk_level',
        'status',
        'incident_date',
        'district',
        'subdistrict',
        'village',
        'address',
        'latitude',
        'longitude',
        'attachment_path',
        'reporter_name',
        'reporter_phone',
        'is_anonymous',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'is_anonymous' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function actions()
    {
        return $this->hasMany(ReportAction::class)->latest();
    }

    public function getRiskBadgeClassAttribute(): string
    {
        return match ($this->risk_level) {
            'red' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
            'yellow' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
            'green' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
            default => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:border-slate-500/20',
        };
    }

    public function getRiskLabelAttribute(): string
    {
        return match ($this->risk_level) {
            'red' => 'Bahaya',
            'yellow' => 'Waspada',
            'green' => 'Aman',
            default => 'Normal',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'in_progress' => 'Dalam Penanganan',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Diproses',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/30',
            'verified' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/30',
            'in_progress' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/30',
            'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/30',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/30',
            default => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:border-slate-500/30',
        };
    }
}
