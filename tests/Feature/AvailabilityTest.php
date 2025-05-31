<?php

namespace Tests\Feature;

use App\Models\DateOverride;
use App\Models\Profile;
use App\Models\User;
use App\Models\WeeklyAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_weekly_availability()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/availability/weekly', [
            'profile_id' => $profile->id,
            'availabilities' => [
                ['day_of_week' => 'Monday', 'start_time' => '09:00', 'end_time' => '17:00'],
            ],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('weekly_availabilities', [
            'profile_id' => $profile->id,
            'day_of_week' => 'Monday',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ]);
    }

    public function test_set_override_availability()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/availability/override', [
            'profile_id' => $profile->id,
            'date' => '2024-07-01',
            'start_time' => '10:00',
            'end_time' => '16:00',
            'is_available' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('date_overrides', [
            'profile_id' => $profile->id,
            'date' => '2024-07-01',
            'start_time' => '10:00:00',
            'end_time' => '16:00:00',
            'is_available' => true,
        ]);
    }

    public function test_get_availability_for_buyer()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $buyer = User::factory()->create();

        WeeklyAvailability::create([
            'profile_id' => $profile->id,
            'day_of_week' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        DateOverride::create([
            'profile_id' => $profile->id,
            'date' => '2024-07-01',
            'start_time' => '10:00',
            'end_time' => '16:00',
            'is_available' => false,
        ]);

        $response = $this->actingAs($buyer)->get('/availability/' . $profile->id . '/buyer?timezone=' . urlencode('America/New_York'));

        $response->assertStatus(200);
        $response->assertJson([
            'availabilities' => [
                ['day_of_week' => 'Monday', 'start_time' => '09:00', 'end_time' => '17:00'],
            ],
            'overrides' => [
                ['date' => '2024-07-01', 'start_time' => '10:00', 'end_time' => '16:00', 'is_available' => false],
            ],
        ]);
    }
}
