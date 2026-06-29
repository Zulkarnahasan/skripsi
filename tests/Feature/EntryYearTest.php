<?php

namespace Tests\Feature;

use App\Models\Alternative;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryYearTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_keeps_entry_year_empty(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pendaftar Baru',
            'kip_account_number' => 'KIP-2026-001',
            'school_origin' => 'SMA Negeri 1',
            'study_program' => 'teknik-informatika-s1',
            'study_program_2' => 'manajemen-s1',
            'nisn' => '1234567890',
            'npsn' => '98765432',
            'phone' => '081234567890',
            'password' => 'password-demo',
            'password_confirmation' => 'password-demo',
        ]);

        $response->assertRedirect('/user/dashboard');
        $this->assertDatabaseHas('student_profiles', [
            'nisn' => '1234567890',
            'entry_year' => null,
        ]);
    }

    public function test_registration_requires_numeric_nisn_and_npsn_lengths(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pendaftar Invalid',
            'kip_account_number' => 'KIP-2026-002',
            'school_origin' => 'SMA Negeri 2',
            'study_program' => 'teknik-informatika-s1',
            'study_program_2' => 'manajemen-s1',
            'nisn' => '12345abc90',
            'npsn' => '1234567',
            'phone' => '081234567891',
            'password' => 'password-demo',
            'password_confirmation' => 'password-demo',
        ]);

        $response->assertSessionHasErrors(['nisn', 'npsn']);
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
            'study_program' => 'teknik-informatika-s1',
            'study_program_2' => 'manajemen-s1',
            'entry_year' => 2026,
            'graduation_year' => 2025,
            'nisn' => $profile->nisn,
            'npsn' => $profile->npsn,
            'phone' => $profile->phone,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('student_profiles', [
            'id' => $profile->id,
            'entry_year' => 2026,
            'graduation_year' => 2025,
        ]);
    }

    public function test_incomplete_profile_can_open_documents_and_test_notice(): void
    {
        $user = $this->createApplicant('Peserta Belum Lengkap', 2026);

        $this->actingAs($user)
            ->get('/user/documents')
            ->assertOk()
            ->assertSee('Upload Dokumen')
            ->assertSee('Scan KTP');

        $this->actingAs($user)
            ->get('/user/test')
            ->assertOk()
            ->assertSee('Lengkapi profil terlebih dahulu sebelum mengisi tes.');
    }

    private function createApplicant(string $name, int $entryYear): User
    {
        $user = User::factory()->create(['name' => $name, 'role' => 'user']);
        $profile = $user->studentProfile()->create([
            'kip_account_number' => 'KIP-'.$entryYear.'-'.$user->id,
            'school_origin' => 'SMA Test',
            'study_program' => 'Teknik Informatika',
            'study_program_accreditation' => 'Unggul',
            'study_program_2' => 'Sarjana Manajemen',
            'study_program_accreditation_2' => 'Unggul',
            'entry_year' => $entryYear,
            'graduation_year' => $entryYear - 1,
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
