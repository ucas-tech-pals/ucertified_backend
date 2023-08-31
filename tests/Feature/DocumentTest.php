<?php

namespace Tests\Feature;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_retrieve_his_documents(): void
    {
        Sanctum::actingAs($user = User::factory()->create());

        Institution::factory()->count(3)->create()->each(function ($institution) use ($user) {
            $user->documents()->createMany(
                $institution->documents()->make()->toArray()
            );
        });

        $response = $this->get(route('documents.index'));

        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'institution_id',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'institution' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_upload_document()
    {
        $response = $this->post(route('documents.store'), [
            'name' => 'test',
            'institution_id' => 1,
            'file' => 'test.pdf',
        ]);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'institution_id',
                'user_id',
                'created_at',
                'updated_at',
                'institution' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $response->assertStatus(201);
    }

    public function test_institution_can_retrieve_uploaded_documents()
    {
        Sanctum::actingAs($institution = Institution::factory()->create());

        User::factory()->count(3)->create()->each(function ($user) use ($institution) {
            $user->documents()->createMany(
                $institution->documents()->make()->toArray()
            );
        });

        $response = $this->get(route('documents.index'));

        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'institution_id',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'user' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }
}
