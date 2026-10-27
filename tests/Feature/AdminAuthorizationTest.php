<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_admin_product_creation(): void
    {
        $this->get(route('admin.products.create'))->assertRedirect(route('login'));
    }

    public function test_regular_customer_cannot_open_admin_product_creation(): void
    {
        $role = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.products.create'))->assertForbidden();
    }

    public function test_admin_can_open_admin_product_creation(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.products.create'))->assertOk();
    }
}
