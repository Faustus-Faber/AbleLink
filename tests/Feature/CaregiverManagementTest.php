<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CaregiverManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_caregiver_can_view_dashboard()
    {
        $caregiver = User::factory()->create(['role' => 'caregiver']);

        $response = $this->actingAs($caregiver)->get(route('caregiver.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Caregiver Dashboard');
    }

    public function test_caregiver_can_send_request_to_patient()
    {
        $caregiver = User::factory()->create(['role' => 'caregiver']);
        $patient = User::factory()->create(['role' => 'user', 'email' => 'patient@example.com']);

        $response = $this->actingAs($caregiver)
            ->post(route('caregiver.request'), ['email' => 'patient@example.com']);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('caregiver_user', [
            'caregiver_id' => $caregiver->id,
            'user_id' => $patient->id,
            'status' => 'pending',
        ]);
    }

    public function test_caregiver_cannot_send_request_if_already_linked()
    {
        $caregiver = User::factory()->create(['role' => 'caregiver']);
        $patient = User::factory()->create(['role' => 'user']);
        
        $caregiver->patients()->attach($patient->id, ['status' => 'pending']);

        $response = $this->actingAs($caregiver)
            ->post(route('caregiver.request'), ['email' => $patient->email]);

        $response->assertSessionHasErrors('email');
    }

    public function test_patient_can_accept_request()
    {
        $caregiver = User::factory()->create(['role' => 'caregiver']);
        $patient = User::factory()->create(['role' => 'user']);
        
        $caregiver->patients()->attach($patient->id, ['status' => 'pending']);

        $response = $this->actingAs($patient)
            ->post(route('user.requests.approve', $caregiver));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('caregiver_user', [
            'caregiver_id' => $caregiver->id,
            'user_id' => $patient->id,
            'status' => 'active',
        ]);
    }

    public function test_caregiver_can_update_patient_profile()
    {
        $caregiver = User::factory()->create(['role' => 'caregiver']);
        $patient = User::factory()->create(['role' => 'user']);
        
        $caregiver->patients()->attach($patient->id, ['status' => 'active']);
        $patient->profile()->create([]);

        $response = $this->actingAs($caregiver)
            ->put(route('caregiver.patient.update', $patient), [
                'bio' => 'Updated Bio',
                'accessibility_preferences' => ['font_size' => 'large'],
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $patient->id,
            'bio' => 'Updated Bio',
        ]);
        
        $patient->refresh();
        $this->assertEquals('large', $patient->profile->accessibility_preferences['font_size']);
    }

    public function test_single_caregiver_policy()
    {
        $caregiver1 = User::factory()->create(['role' => 'caregiver']);
        $caregiver2 = User::factory()->create(['role' => 'caregiver']);
        $patient = User::factory()->create(['role' => 'user', 'email' => 'busy@example.com']);

        // Caregiver 1 is already active
        $caregiver1->patients()->attach($patient->id, ['status' => 'active']);

        // Caregiver 2 tries to send request
        $response = $this->actingAs($caregiver2)
            ->post(route('caregiver.request'), ['email' => 'busy@example.com']);

        // Should return error because patient already has active caregiver
        $response->assertSessionHasErrors('email'); 
    }
}
