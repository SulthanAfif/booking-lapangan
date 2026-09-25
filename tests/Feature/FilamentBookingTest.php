<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_tidak_bisa_akses_panel_admin(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertForbidden();
    }

    public function test_admin_bisa_akses_panel_admin(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
    }
}