<?php

use App\Models\Assessment;
use App\Models\Materi;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('api app-data returns successful response with structure', function () {
    $response = $this->getJson('/api/app-data');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'records',
            'schools',
            'materis',
            'videos',
            'faqs',
            'researchers',
            'appSettings',
            'dbDriver',
            'isAdmin',
        ]);
});

test('admin login rejects invalid credentials', function () {
    $response = $this->postJson('/api/admin/login', [
        'username' => 'wrongadmin',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
        ]);
});

test('admin login succeeds with valid credentials', function () {
    $response = $this->postJson('/api/admin/login', [
        'username' => 'admin',
        'password' => 'sabawa2026',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'isAdmin' => true,
        ]);

    $this->assertTrue(session('admin_logged_in'));
});

test('unauthenticated user cannot delete assessment', function () {
    $assessment = Assessment::create([
        'nama' => 'Test Athlete',
        'nim' => '12345678',
        'jenis_kelamin' => 'L',
        'kelas' => 'Palembang A 2024',
        'sekolah' => 'SMAN 1',
        'tanggal' => now(),
        'skor_servis_pendek' => 15,
        'skor_servis_panjang' => 15,
        'skor_lob' => 15,
        'skor_smash' => 15,
    ]);

    $response = $this->deleteJson("/api/assessments/{$assessment->id}");

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
        ]);
});

test('admin can delete assessment', function () {
    $assessment = Assessment::create([
        'nama' => 'Athlete To Delete',
        'nim' => '99999999',
        'jenis_kelamin' => 'P',
        'kelas' => 'Palembang A 2024',
        'sekolah' => 'SMAN 2',
        'tanggal' => now(),
        'skor_servis_pendek' => 10,
        'skor_servis_panjang' => 10,
        'skor_lob' => 10,
        'skor_smash' => 10,
    ]);

    $response = $this->withSession(['admin_logged_in' => true])
        ->deleteJson("/api/assessments/{$assessment->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseMissing('assessments', [
        'id' => $assessment->id,
    ]);
});

test('unauthenticated user cannot update settings', function () {
    $response = $this->postJson('/api/settings', [
        'app_name' => 'Hacked Name',
    ]);

    $response->assertStatus(401);
});

test('storing assessment strips malicious html tags to prevent xss', function () {
    $response = $this->postJson('/api/assessments', [
        'nama' => '<b>Budi</b><script>alert("hack")</script>',
        'nim' => '06061282227001',
        'jenisKelamin' => 'L',
        'kelas' => 'Palembang A',
        'sekolah' => 'SMA 1 <img src=x onerror=alert(1)>',
        'tanggal' => '2026-09-15',
        'penguji' => 'Silvi Aryanti, M.Pd.',
        'trialsServisPendek' => array_fill(0, 20, 2),
        'skorServisPendek' => 40,
        'trialsServisPanjang' => array_fill(0, 20, 2),
        'skorServisPanjang' => 40,
        'trialsLob' => array_fill(0, 20, 2),
        'skorLob' => 40,
        'trialsSmash' => array_fill(0, 20, 2),
        'skorSmash' => 40,
    ]);

    $response->assertStatus(200);

    $record = Assessment::where('nim', '06061282227001')->first();
    $this->assertNotNull($record);
    $this->assertEquals('Budi', $record->nama);
    $this->assertStringNotContainsString('<script>', $record->nama);
    $this->assertEquals('SMA 1', $record->sekolah);
    $this->assertStringNotContainsString('onerror', $record->sekolah);
});
