<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AkunTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testEditAkunValid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $roles = [
            'admin' => 'admin.akun.index',
            'guru' => 'guru.akun.index',
            'orangtua' => 'siswa.akun.index',
        ];

        foreach ($roles as $role => $expectedRoute) {
            $user = User::factory()->create([
                'username' => $role . '_lama',
                'email' => $role . '@example.com',
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $updateData = [
                'username' => $role . '_baru',
                'email' => $role . '_baru@example.com',
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ];

            $response = $this->put(route('akun.update', $user), $updateData);

            $response->assertRedirect(route($expectedRoute));

            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'username' => $updateData['username'],
                'email' => $updateData['email'],
            ]);
        }
    }

    public function testEditAkunInvalid()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $roles = [
            'admin' => 'admin.akun.index',
            'guru' => 'guru.akun.index',
            'orangtua' => 'siswa.akun.index',
        ];

        foreach ($roles as $role => $redirect) {
            $user = User::factory()->create([
                'username' => $role . '_lama',
                'email' => $role . '@example.com',
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $updateData = [
                'username' => $role . '_baru',
                'email' => $role . '_baru@example.com',
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123_invalid',
            ];

            $response = $this->put(route('akun.update', $user), $updateData);

            $response->assertSessionHasErrors(['password']);
        }
    }

    public function testViewAkunList()
    {
        $admin = User::factory()->create([
            'email' => 'admin@siakad-slbdwsidoarjo.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $roles = [
            'admin' => 'admin.akun.index',
            'guru' => 'guru.akun.index',
            'orangtua' => 'siswa.akun.index',
        ];

        foreach ($roles as $role => $expectedRoute) {
            User::factory()->create([
                'username' => $role . '_example',
                'email' => "$role@example.com",
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $response = $this->get(route($expectedRoute));

            if (in_array($role, ['admin', 'guru', 'orangtua'])) {
                $response->assertSeeText('Data Akun');
            }
        }
    }

    public function testEditAkunValidForGuruAndSiswa()
    {
        $roles = [
            'guru' => ['route' => 'akun.update.guru', 'redirect' => 'guru.dashboard'],
            'orangtua' => ['route' => 'akun.update.siswa', 'redirect' => 'siswa.dashboard'],
        ];

        foreach ($roles as $role => $routes) {
            $user = User::factory()->create([
                'role' => $role,
                'username' => $role . '_lama',
                'email' => $role . '@example.com',
                'password' => Hash::make('password123'),
            ]);

            $this->actingAs($user);

            $response = $this->put(route($routes['route']), [
                'username' => $role . '_baru',
                'email' => $role . '_baru@example.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

            $response->assertRedirect(route($routes['redirect']));

            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'username' => $role . '_baru',
                'email' => $role . '_baru@example.com',
            ]);
        }
    }

    public function testEditAkunInvalidForGuruAndSiswa()
    {
        $roles = [
            'guru' => 'akun.update.guru',
            'orangtua' => 'akun.update.siswa',
        ];

        foreach ($roles as $role => $route) {
            $user = User::factory()->create([
                'role' => $role,
                'username' => $role . '_lama',
                'email' => $role . '@example.com',
                'password' => Hash::make('password123'),
            ]);

            $this->actingAs($user);

            $response = $this->put(route($route), [
                'username' => '',
                'email' => 'notanemail',
                'password' => '123',
                'password_confirmation' => '456',
            ]);

            $response->assertSessionHasErrors(['username', 'email', 'password']);
        }
    }
}
