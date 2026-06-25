<?php

namespace Tests\Feature;

use App\Models\HealthCheck;
use App\Models\HealthThreshold;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminFunctionalTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed thresholds
        $this->seed(\Database\Seeders\HealthThresholdSeeder::class);

        // Create admin
        $this->admin = User::factory()->create([
            'name'      => 'Admin',
            'email'     => 'admin@test.com',
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }

    // ===================== USER INDEX =====================

    public function test_user_search_filters_results(): void
    {
        User::factory()->create(['name' => 'Bob Marley', 'role' => 'user']);
        User::factory()->create(['name' => 'Alice Jones', 'role' => 'user']);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Users\UserIndex::class)
            ->set('search', 'Bob');

        $test->assertSee('Bob Marley');
        $test->assertDontSee('Alice Jones');
    }

    public function test_user_department_filter(): void
    {
        User::factory()->create(['name' => 'Eng User', 'role' => 'user', 'department' => 'Engineering']);
        User::factory()->create(['name' => 'HR User',  'role' => 'user', 'department' => 'HR']);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Users\UserIndex::class)
            ->set('department', 'Engineering');

        $test->assertSee('Eng User');
        $test->assertDontSee('HR User');
    }

    public function test_user_status_filter(): void
    {
        User::factory()->create(['name' => 'Active User',   'role' => 'user', 'is_active' => true]);
        User::factory()->create(['name' => 'Inactive User', 'role' => 'user', 'is_active' => false]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Users\UserIndex::class)
            ->set('status', 'active');

        $test->assertSee('Active User');
        $test->assertDontSee('Inactive User');
    }

    public function test_toggle_user_status(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);

        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Users\UserIndex::class)
            ->call('toggleStatus', $user->id);

        $this->assertFalse($user->fresh()->is_active);
    }

    // ===================== HEALTH CHECK INDEX =====================

    public function test_health_check_search_by_user_name(): void
    {
        $user1 = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'user']);
        $user2 = User::factory()->create(['name' => 'Siti Rahayu', 'role' => 'user']);

        HealthCheck::create([
            'user_id' => $user1->id, 'check_date' => '2026-06-10',
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);
        HealthCheck::create([
            'user_id' => $user2->id, 'check_date' => '2026-06-10',
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\HealthChecks\HealthCheckIndex::class)
            ->set('search', 'Budi');

        $test->assertSee('Budi Santoso');
        $test->assertDontSee('Siti Rahayu');
    }

    public function test_health_check_date_filter(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => '2026-06-10',
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);
        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => '2026-06-20',
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\HealthChecks\HealthCheckIndex::class)
            ->set('dateFrom', '2026-06-15')
            ->set('dateTo', '2026-06-25');

        $this->assertEquals(1, $test->viewData('checks')->total());
    }

    public function test_health_check_status_filter(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => '2026-06-10',
            'overall_status' => 'attention', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'high', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);
        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => '2026-06-11',
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\HealthChecks\HealthCheckIndex::class)
            ->set('status', 'attention');

        $this->assertEquals(1, $test->viewData('checks')->total());
    }

    // ===================== HEALTH CHECK EDIT =====================

    public function test_health_check_edit_loads_existing_data(): void
    {
        $user = User::factory()->create(['role' => 'user', 'gender' => 'male']);
        $hc = HealthCheck::create([
            'user_id'              => $user->id,
            'check_date'           => '2026-06-10',
            'fasting_blood_sugar'  => 95.5,
            'uric_acid'            => 5.2,
            'cholesterol'          => 180,
            'systolic'             => 120,
            'diastolic'            => 80,
            'notes'                => 'Test note',
            'overall_status'       => 'normal',
            'created_by'           => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal',
            'random_blood_sugar_status'  => 'unmeasured',
            'uric_acid_status'           => 'normal',
            'cholesterol_status'         => 'normal',
            'blood_pressure_status'      => 'optimal',
        ]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\HealthChecks\HealthCheckEdit::class, ['healthCheck' => $hc]);

        $this->assertEquals((string) $user->id, $test->get('user_id'));
        $this->assertEquals('2026-06-10', $test->get('check_date'));
        $this->assertStringContainsString('95', $test->get('fasting_blood_sugar'));
        $this->assertEquals('Test note', $test->get('notes'));
    }

    // ===================== THRESHOLD EDIT =====================

    public function test_threshold_edit_loads_and_saves(): void
    {
        $threshold = HealthThreshold::first();
        $originalValue = $threshold->abnormal_value;

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Thresholds\ThresholdIndex::class);

        // Start edit
        $test->call('startEdit', $threshold->id);
        $this->assertEquals($threshold->id, $test->get('editingId'));
        $this->assertEquals((string) $originalValue, $test->get('abnormal_value'));

        // Update value
        $test->set('abnormal_value', '105')
             ->call('saveThreshold');

        $this->assertNull($test->get('editingId'));
        $this->assertDatabaseHas('health_thresholds', [
            'id'             => $threshold->id,
            'abnormal_value' => 105,
        ]);
    }

    // ===================== REPORT =====================

    public function test_report_tab_switching(): void
    {
        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Reports\ReportIndex::class);

        $this->assertEquals('date', $test->get('reportType'));

        $test->call('setReportType', 'abnormal');
        $this->assertEquals('abnormal', $test->get('reportType'));

        $test->call('setReportType', 'parameter');
        $this->assertEquals('parameter', $test->get('reportType'));
    }

    public function test_report_abnormal_filter(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => now()->format('Y-m-d'),
            'overall_status' => 'attention', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'high', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);
        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => now()->format('Y-m-d'),
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'unmeasured', 'blood_pressure_status' => 'unmeasured',
        ]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Reports\ReportIndex::class);

        $test->call('setReportType', 'abnormal');
        $this->assertEquals(1, $test->viewData('checks')->total());
    }

    public function test_report_parameter_filter(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => now()->format('Y-m-d'),
            'cholesterol' => 250, 'overall_status' => 'attention', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'unmeasured', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'high', 'blood_pressure_status' => 'unmeasured',
        ]);
        HealthCheck::create([
            'user_id' => $user->id, 'check_date' => now()->subDay()->format('Y-m-d'),
            'overall_status' => 'normal', 'created_by' => $this->admin->id,
            'fasting_blood_sugar_status' => 'normal', 'random_blood_sugar_status' => 'unmeasured',
            'uric_acid_status' => 'unmeasured', 'cholesterol_status' => 'normal', 'blood_pressure_status' => 'unmeasured',
        ]);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Reports\ReportIndex::class);

        $test->call('setReportType', 'parameter')
             ->set('parameter', 'cholesterol');

        $this->assertEquals(1, $test->viewData('checks')->total());
    }

    // ===================== PAGINATION =====================

    public function test_user_list_paginates(): void
    {
        User::factory(20)->create(['role' => 'user']);

        $test = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\Users\UserIndex::class);

        $users = $test->viewData('users');
        $this->assertEquals(15, $users->perPage());
        $this->assertTrue($users->hasPages());
        $this->assertGreaterThan(15, $users->total());
    }
}
