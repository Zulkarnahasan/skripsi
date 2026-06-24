<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TestSetting extends Model
{
    protected $fillable = ['duration_minutes', 'selection_quota', 'registration_open', 'is_open', 'instruction'];

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
            'registration_open' => 'boolean',
        ];
    }

    public static function current(): self
    {
        $defaults = [
            'duration_minutes' => 60,
            'selection_quota' => 10,
            'registration_open' => true,
            'is_open' => false,
            'instruction' => 'Tes sudah dapat dimulai. Kerjakan semua soal sesuai waktu yang ditentukan.',
        ];

        if (! Schema::hasTable('test_settings')) {
            return new static($defaults);
        }

        $createDefaults = [
            'duration_minutes' => $defaults['duration_minutes'],
            'is_open' => $defaults['is_open'],
            'instruction' => $defaults['instruction'],
        ];

        if (Schema::hasColumn('test_settings', 'selection_quota')) {
            $createDefaults['selection_quota'] = $defaults['selection_quota'];
        }

        if (Schema::hasColumn('test_settings', 'registration_open')) {
            $createDefaults['registration_open'] = $defaults['registration_open'];
        }

        $setting = static::query()->firstOrCreate([], $createDefaults);

        if (! $setting->instruction) {
            $setting->forceFill([
                'instruction' => 'Tes sudah dapat dimulai. Kerjakan semua soal sesuai waktu yang ditentukan.',
            ])->save();
        }

        if (Schema::hasColumn('test_settings', 'registration_open') && $setting->registration_open === null) {
            $setting->forceFill(['registration_open' => true])->save();
        }

        if (Schema::hasColumn('test_settings', 'selection_quota') && ! $setting->selection_quota) {
            $setting->forceFill(['selection_quota' => 10])->save();
        }

        return $setting;
    }
}
