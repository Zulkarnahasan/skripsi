<?php

namespace Tests\Feature;

use App\Models\Alternative;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryYearTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_saves_entry_year(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pendaftar Baru',
            'kip_account_number' => 'KIP-2026-001',
            'school_origin' => 'SMA Negeri 1',
            'entry_year' => 2026,
            'nisn' => '1234567890',
            'npsn' => '98765432',
            'phone' => '081234567890',
        ]);

        $response->assertRedirect('/user/dashboard');
        $this->assertDatabaseHas('student_profiles', [
            'nisn' => '1234567890',
            'entry_year' => 2026,
        ]);
    }

    public function test_admin_can_filter_applicants_by_entry_year(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $included = $this->createApplicant('Mahasiswa 2026', 2026);
        $excluded = $this->createApplicant('Mahasiswa 2025', 2025);

        $response = $this->actingAs($admin)->get('/admin/applicants?entry_year=2026');

        $response->assertOk()
            ->assertSee($included->name)
            ->assertDontSee($excluded->name);
    }

    public function test_admin_can_filter_users_by_entry_year(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $included = $this->createApplicant('User Angkatan 2026', 2026);
        $excluded = $this->createApplicant('User Angkatan 2025', 2025);

        $response = $this->actingAs($admin)->get('/admin/users?entry_year=2026');

        $response->assertOk()
            ->assertSee($included->name)
            ->assertDontSee($excluded->name);
    }

    public function test_participant_can_update_entry_year(): void
    {
        $user = $this->createApplicant('Peserta Update Tahun', 2025);
        $profile = $user->studentProfile;

        $response = $this->actingAs($user)->post('/user/profile', [
            'name' => $user->name,
            'kip_account_number' => $profile->kip_account_number,
            'school_origin' => $profile->school_origin,
            'entry_year' => 2026,
            'nisn' => $profile->nisn,
            'npsn' => $profile->npsn,
            'phone' => $profile->phone,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('student_profiles', [
            'id' => $profile->id,
            'entry_year' => 2026,
        ]);
    }

    private function createApplicant(string $name, int $entryYear): User
    {
        $user = User::factory()->create(['name' => $name, 'role' => 'user']);
        $profile = $user->studentProfile()->create([
            'kip_account_number' => 'KIP-'.$entryYear.'-'.$user->id,
            'school_origin' => 'SMA Test',
            'entry_year' => $entryYear,
            'nisn' => 'NISN-'.$user->id,
            'npsn' => 'NPSN-'.$user->id,
            'phone' => '0812'.$user->id,
            'status' => 'submitted',
        ]);

        Alternative::query()->create([
            'user_id' => $user->id,
            'student_profile_id' => $profile->id,
            'registration_number' => 'REG-'.$user->id,
            'status' => 'submitted',
        ]);

        return $user;
    }
}
