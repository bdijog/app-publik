<?php

namespace Tests\Feature;

use App\Models\Employee;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response->assertSuccessful();
    }

    public function test_active_employee_can_authenticate_and_access_admin_panel(): void
    {
        $employee = Employee::factory()->create([
            'email' => 'admin@labkesmas-ambon.go.id',
            'password' => 'password',
            'is_active' => true,
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'admin@labkesmas-ambon.go.id',
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($employee, 'employee');
    }

    public function test_inactive_employee_cannot_authenticate(): void
    {
        $employee = Employee::factory()->inactive()->create([
            'email' => 'inactive@labkesmas-ambon.go.id',
            'password' => 'password',
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'inactive@labkesmas-ambon.go.id',
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors();

        $this->assertGuest('employee');
    }

    public function test_authenticated_active_employee_can_access_admin_dashboard(): void
    {
        $employee = Employee::factory()->create(['is_active' => true]);

        $response = $this->actingAs($employee, 'employee')->get('/admin');

        $response->assertSuccessful();
    }

    public function test_authenticated_inactive_employee_is_forbidden_from_admin_panel(): void
    {
        $employee = Employee::factory()->inactive()->create();

        $response = $this->actingAs($employee, 'employee')->get('/admin');

        $response->assertForbidden();
    }

    public function test_employee_last_login_at_is_updated_on_successful_authentication(): void
    {
        $employee = Employee::factory()->create([
            'email' => 'petugas@labkesmas-ambon.go.id',
            'password' => 'password',
            'is_active' => true,
            'last_login_at' => null,
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'petugas@labkesmas-ambon.go.id',
                'password' => 'password',
            ])
            ->call('authenticate');

        $this->assertNotNull($employee->fresh()->last_login_at);
    }
}
