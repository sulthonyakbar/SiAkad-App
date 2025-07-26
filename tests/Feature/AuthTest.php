<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    protected function createUser(string $role, string $username = null, string $password = 'password123'): User
    {
        $user = User::factory()->create([
            'username' => $username ?? $role . 'user',
            'email' => "{$role}@example.com",
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        if (in_array($role, ['admin', 'guru'])) {
            Guru::factory()->create(['user_id' => $user->id, 'nama_guru' => 'Guru Testing']);
        } elseif ($role === 'orangtua') {
            Siswa::factory()->create(['user_id' => $user->id, 'nama_siswa' => 'Siswa Testing']);
        }

        return $user;
    }

    /**
     * Test login valid untuk semua role.
     */
    public function testLoginWithValidCredentials()
    {
        $this->withoutMiddleware();

        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $password = $role . '123';
            $user = $this->createUser($role, $role, $password);

            $response = $this->post('/', [
                'login' => $user->username,
                'password' => $password,
            ]);

            $route = match ($role) {
                'admin' => route('admin.dashboard'),
                'guru' => route('guru.dashboard'),
                'orangtua' => route('siswa.dashboard'),
            };

            $response->assertRedirect($route);

            $this->assertAuthenticatedAs($user);

            $this->post(route('logout'));
        }
    }

    public function testLoginWithInvalidCredentials()
    {
        $this->withoutMiddleware();

        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $password = $role . '123';
            $user = $this->createUser($role, $role, $password);

            $response = $this->post('/', [
                'login' => $user->username,
                'password' => 'wrongpassword',
            ]);

            $response->assertRedirect(route('login'));

            $response->assertSessionHas('error', 'Password yang Anda masukkan salah.');

            $this->assertGuest();
        }
    }

    public function testLoginWithNonExistentUsername()
    {
        $this->withoutMiddleware();

        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $password = $role . '123';
            $user = $this->createUser($role, $role, $password);

            $response = $this->post('/', [
                'login' => 'nonexistentuser',
                'password' => $password,
            ]);

            $response->assertRedirect(route('login'));

            $response->assertSessionHas('error', 'Username tidak ditemukan.');

            $this->assertGuest();
        }
    }

    public function testLoginWithNonExistentEmail()
    {
        $this->withoutMiddleware();

        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $password = $role . '123';
            $user = $this->createUser($role, $role, $password);

            $response = $this->post('/', [
                'login' => 'nonexistentemail@example.com',
                'password' => $password,
            ]);

            $response->assertRedirect(route('login'));

            $response->assertSessionHas('error', 'Email tidak ditemukan.');

            $this->assertGuest();
        }
    }

    public function testLoginWithSpecialCharacters()
    {
        $this->withoutMiddleware();

        $specialInputs = [
            "admin' OR '1'='1",
            "'; DROP TABLE users; --",
            "<script>alert('xss')</script>",
            "!@#$%^&*()_+-=[]{}|;:',.<>/?",
        ];

        foreach ($specialInputs as $input) {
            $response = $this->post('/', [
                'login' => $input,
                'password' => 'anypassword',
            ]);
            $response->assertRedirect(route('login'));

            $response->assertSessionHas('error', 'Username tidak ditemukan.');

            $this->assertGuest();
        }
    }

    // public function testRedirectToLoginIfNotAuthenticated()
    // {
    //     $protectedRoutes = [
    //         route('admin.dashboard'),
    //         route('guru.dashboard'),
    //         route('siswa.dashboard'),
    //     ];

    //     foreach ($protectedRoutes as $route) {
    //         $response = $this->get($route);

    //         $response->assertRedirect(route('login'));

    //         $this->assertGuest();
    //     }
    // }

    public function testLogoutForAllRoles()
    {
        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $user = $this->createUser($role);

            $this->actingAs($user);

            $response = $this->delete(route('logout'));

            $response->assertRedirect(route('login'));

            $this->assertGuest();
        }
    }

    public function testLoginViewIsReturned()
    {
        $response = $this->get(route('login'));

        $response->assertViewIs('pages.auth-login');
    }
}
