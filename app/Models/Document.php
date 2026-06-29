<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['user_id', 'student_profile_id', 'document_type', 'file_path', 'status'];

    public const TYPES = [
        'participant_card_form_kip_account' => [
            'label' => 'Scan Kartu Peserta, Formulir, dan Akun KIP',
            'required' => true,
        ],
        'assistance_card' => [
            'label' => 'Scan Kartu KIP/KKS/PKH/DTKS/PPKE/SKTM',
            'required' => true,
        ],
        'ktp' => [
            'label' => 'Scan KTP',
            'required' => true,
        ],
        'family_card' => [
            'label' => 'Scan Kartu Keluarga',
            'required' => true,
        ],
        'certificate' => [
            'label' => 'Scan Ijazah/SKL',
            'required' => true,
        ],
        'electricity_payment' => [
            'label' => 'Scan Bukti Pembayaran Listrik',
            'required' => true,
        ],
        'land_building_tax' => [
            'label' => 'Scan Pajak Bumi Bangunan',
            'required' => true,
        ],
        'achievement_certificate' => [
            'label' => 'Scan Sertifikat Prestasi',
            'required' => false,
        ],
    ];

    public static function typeKeys(): array
    {
        return array_keys(self::TYPES);
    }

    public static function typeLabel(string $type): string
    {
        return self::TYPES[$type]['label'] ?? $type;
    }

    public static function requiredTypeKeys(): array
    {
        return array_keys(array_filter(self::TYPES, fn ($type) => $type['required']));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
