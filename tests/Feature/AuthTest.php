<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    protected function createUser(string $role, string $username = null, string $password = 'password123'): User
    {
        return User::factory()->create([
            'username' => $username ?? $role . 'user',
            'password' => Hash::make($password),
            'role' => $role,
        ]);
    }

    /**
     * Test login valid untuk semua role.
     */
    public function testLoginWithValidCredentials()
    {
        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $password = $role . '123';
            $user = $this->createUser($role, $role, $password);

            $response = $this->post('/', [
                'login' => $user->username,
                'password' => $password,
            ]);

            $response->assertRedirect(route("{$role}.dashboard"));

            $this->post(route('logout'));
        }
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->post('/', [
            'login' => 'qwerty',
            'password' => 'qwerty123',
        ]);

        $response->assertSessionHas('error', 'Username atau Password salah');
        $response->assertRedirect('/');
    }

    public function testLoginWithEmptyInputs()
    {
        $response = $this->post('/', ['login' => '', 'password' => 'somepass']);
        $response->assertSessionHasErrors(['login']);

        $response = $this->post('/', ['login' => 'someuser', 'password' => '']);
        $response->assertSessionHasErrors(['password']);
    }

    public function testLoginWithSpecialCharacters()
    {
        $response = $this->post('/', [
            'login' => '@!@#&*&*!',
            'password' => 'admin123',
        ]);
        $response->assertSessionHas('error', 'Username atau Password salah');
        $response->assertRedirect('/');

        $response = $this->post('/', [
            'login' => 'admin',
            'password' => '@!@#&*&*!',
        ]);
        $response->assertSessionHas('error', 'Username atau Password salah');
        $response->assertRedirect('/');
    }

    public function testRedirectToLoginIfNotAuthenticated()
    {
        $urls = [
            'admin.dashboard' => route('admin.dashboard'),
            'guru.dashboard' => route('guru.dashboard'),
            'siswa.dashboard' => route('siswa.dashboard'),
        ];

        foreach ($urls as $role => $url) {
            $response = $this->get($url);
            $response->assertRedirect(route('login'));
            $this->assertGuest();
        }
    }

   public function testLogoutForAllRoles()
    {
        foreach (['admin', 'guru', 'orangtua'] as $role) {
            $user = $this->createUser($role);
            $this->actingAs($user);

            $response = $this->post(route('logout'));

            $response->assertRedirect(route('login'));
            $this->assertGuest();
        }
    }
}
