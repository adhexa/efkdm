<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'institution_name',
        'position_title',
        'sk_number',
        'sk_document_path',
        'district',
        'subdistrict',
        'village',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function actions()
    {
        return $this->hasMany(ReportAction::class);
    }

    public function isVendorAdmin(): bool
    {
        return $this->role === 'vendor_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'vendor_admin';
    }

    public function isFkdmKabupaten(): bool
    {
        return $this->role === 'fkdm_kabupaten' || $this->role === 'admin' || $this->role === 'vendor_admin';
    }

    public function isFkdmMember(): bool
    {
        return $this->role === 'fkdm_member' || $this->role === 'fkdm_kabupaten' || $this->role === 'admin' || $this->role === 'vendor_admin';
    }

    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Memeriksa apakah pengguna berhak mengelola & memverifikasi status laporan.
     * Hanya Kesbangpol Pemda, Vendor Admin, & Pengurus FKDM Kabupaten yang dapat mengubah status.
     * Anggota FKDM Kelurahan / Desa / Kecamatan (fkdm_member) tidak mengelola panel ini.
     */
    public function canManageReport($report): bool
    {
        return $this->isFkdmKabupaten();
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'vendor_admin' => 'Fasilitator Swasta / Konsultan',
            'admin' => 'Kesbangpol / Admin Pemda',
            'fkdm_kabupaten' => 'Pengurus FKDM Kabupaten',
            'fkdm_member' => 'Anggota FKDM',
            default => 'Anggota FKDM',
        };
    }
}
