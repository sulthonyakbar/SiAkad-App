<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Feedback;
use App\Models\AktivitasHarian;
use Illuminate\Support\Facades\Hash;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateFeedbackValid()
    {
        $userSiswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $userSiswa->id,
        ]);

        $this->actingAs($userSiswa);

        $aktivitas = AktivitasHarian::factory()->create([
            'siswa_id' => $siswa->id,
        ]);

        $feedbackData = [
            'pesan' => 'Feedback untuk kegiatan',
            'aktivitas_id' => $aktivitas->id,
        ];

        $response = $this->post(route('feedback.store'), $feedbackData);

        $response->assertRedirect(route('feedback.index'));

        $this->assertDatabaseHas('feedback', [
            'pesan' => 'Feedback untuk kegiatan',
        ]);

        $this->assertNotNull(AktivitasHarian::find($aktivitas->id)->feedback_id);
    }

    public function testCreateFeedbackInvalid()
    {
        $userSiswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $userSiswa->id,
        ]);

        $this->actingAs($userSiswa);

        $aktivitas = AktivitasHarian::factory()->create([
            'siswa_id' => $siswa->id,
        ]);

        $feedbackData = [
            'pesan' => '',
            'aktivitas_id' => $aktivitas->id,
        ];

        $response = $this->post(route('feedback.store'), $feedbackData);

        $response->assertSessionHasErrors(['pesan']);
    }

    public function testEditFeedbackValid()
    {
        $userSiswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $userSiswa->id,
        ]);

        $this->actingAs($userSiswa);

        $aktivitas = AktivitasHarian::factory()->create([
            'siswa_id' => $siswa->id,
        ]);

        $feedback = Feedback::factory()->create([
            'pesan' => 'Feedback untuk kegiatan',
        ]);

        $aktivitas->update([
            'feedback_id' => $feedback->id,
        ]);

        $feedbackData = [
            'pesan' => 'Feedback yang telah diperbarui',
        ];

        $response = $this->put(route('feedback.update', $aktivitas->id), $feedbackData);

        $response->assertRedirect(route('feedback.index'));

        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'pesan' => 'Feedback yang telah diperbarui',
        ]);
    }

    public function testEditFeedbackInvalid()
    {
        $userSiswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $userSiswa->id,
        ]);

        $this->actingAs($userSiswa);

        $aktivitas = AktivitasHarian::factory()->create([
            'siswa_id' => $siswa->id,
        ]);

        $feedback = Feedback::factory()->create([
            'pesan' => 'Feedback untuk kegiatan',
        ]);

        $aktivitas->update([
            'feedback_id' => $feedback->id,
        ]);

        $feedbackData = [
            'pesan' => '',
        ];

        $response = $this->put(route('feedback.update', $feedback->id), $feedbackData);

        $response->assertSessionHasErrors(['pesan']);
    }

    public function testViewFeedbackList()
    {
        $userSiswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $userSiswa->id,
        ]);

        $this->actingAs($userSiswa);

        AktivitasHarian::factory()->create([
            'siswa_id' => $siswa->id,
        ]);

        Feedback::factory()->create([
            'pesan' => 'Feedback untuk kegiatan',
        ]);

        $response = $this->get(route('feedback.index'));

        $response->assertSeeText('Data Feedback Aktivitas Harian Siswa');
    }

    public function testShowDetailFeedback()
    {
        $userSiswa = User::factory()->create([
            'email' => 'siswa@siakad-slbdwsidoarjo.com',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'orangtua'
        ]);

        $siswa = Siswa::factory()->create([
            'user_id' => $userSiswa->id,
        ]);

        $this->actingAs($userSiswa);

        $feedback = Feedback::factory()->create([
            'pesan' => 'Feedback untuk kegiatan',
        ]);

        $aktivitas = AktivitasHarian::factory()->create([
            'siswa_id' => $siswa->id,
            'feedback_id' => $feedback->id,
        ]);

        $response = $this->get(route('feedback.detail', $aktivitas->id));

        $response->assertSeeText('Detail Data Feedback Aktivitas Harian Siswa');
    }

    public function testUnauthorizedUserCannotAccess()
    {
        $guru = User::factory()->create([
            'email' => 'guru@siakad-slbdwsidoarjo.com',
            'username' => 'guru',
            'password' => Hash::make('guru123'),
            'role' => 'guru'
        ]);

        $this->actingAs($guru);

        $response = $this->get(route('feedback.index'));

        $response->assertRedirect(route('login'));
    }
}
