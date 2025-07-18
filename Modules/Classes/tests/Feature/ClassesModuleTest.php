<?php

namespace Modules\Classes\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Classes\Models\Classes;
use Tests\TestCase;

class ClassesModuleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_class()
    {
        $data = [
            'name' => $this->faker->word,
            'academicYear' => $this->faker->year,
        ];

        $response = $this->postJson('/api/v1/classes', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Classe créée avec succès.',
                     'classe' => [
                         'name' => $data['name'],
                         'academic_year' => $data['academicYear'],
                     ],
                 ]);

        $this->assertDatabaseHas('classes', [
            'name' => $data['name'],
            'academic_year' => $data['academicYear'],
        ]);
    }

    /** @test */
    public function it_can_get_all_classes()
    {
        Classes::factory()->create(['name' => 'Class A', 'academic_year' => '2023-2024']);
        Classes::factory()->create(['name' => 'Class B', 'academic_year' => '2024-2025']);

        $response = $this->getJson('/api/v1/classes');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'classes' => [
                         ['name' => 'Class A', 'academic_year' => '2023-2024'],
                         ['name' => 'Class B', 'academic_year' => '2024-2025'],
                     ],
                 ]);
    }

    /** @test */
    public function it_can_get_a_single_class()
    {
        $class = Classes::factory()->create(['name' => 'Test Class', 'academic_year' => '2023-2024']);

        $response = $this->getJson('/api/v1/classes/' . $class->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'classe' => [
                         'name' => 'Test Class',
                         'academic_year' => '2023-2024',
                     ],
                 ]);
    }

    /** @test */
    public function it_can_update_a_class()
    {
        $class = Classes::factory()->create(['name' => 'Old Name', 'academic_year' => '2023-2024']);

        $updatedData = [
            'name' => 'New Name',
            'academicYear' => '2024-2025',
        ];

        $response = $this->putJson('/api/v1/classes/' . $class->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Classe mise à jour avec succès.',
                     'classe' => [
                         'name' => 'New Name',
                         'academic_year' => '2024-2025',
                     ],
                 ]);

        $this->assertDatabaseHas('classes', [
            'id' => $class->id,
            'name' => 'New Name',
            'academic_year' => '2024-2025',
        ]);
    }

    /** @test */
    public function it_can_delete_a_class()
    {
        $class = Classes::factory()->create(['name' => 'Class to Delete', 'academic_year' => '2023-2024']);

        $response = $this->deleteJson('/api/v1/classes/' . $class->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Classe supprimée avec succès.',
                 ]);

        $this->assertDatabaseMissing('classes', [
            'id' => $class->id,
        ]);
    }

    /** @test */
    public function it_returns_404_if_class_not_found_on_show()
    {
        $response = $this->getJson('/api/v1/classes/999');
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_if_class_not_found_on_update()
    {
        $updatedData = [
            'name' => 'New Name',
            'academicYear' => '2024-2025',
        ];
        $response = $this->putJson('/api/v1/classes/999', $updatedData);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_if_class_not_found_on_delete()
    {
        $response = $this->deleteJson('/api/v1/classes/999');
        $response->assertStatus(404);
    }

    /** @test */
    public function it_validates_required_fields_on_create()
    {
        $response = $this->postJson('/api/v1/classes', []);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'academicYear']);
    }

    /** @test */
    public function it_validates_required_fields_on_update()
    {
        $class = Classes::factory()->create();
        $response = $this->putJson('/api/v1/classes/' . $class->id, []);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'academicYear']);
    }

    /** @test */
    public function it_validates_unique_name_on_create()
    {
        Classes::factory()->create(['name' => 'Existing Class', 'academic_year' => '2023-2024']);
        $data = [
            'name' => 'Existing Class',
            'academicYear' => '2024-2025',
        ];
        $response = $this->postJson('/api/v1/classes', $data);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_validates_unique_name_on_update()
    {
        Classes::factory()->create(['name' => 'Existing Class 1', 'academic_year' => '2023-2024']);
        $class2 = Classes::factory()->create(['name' => 'Existing Class 2', 'academic_year' => '2024-2025']);

        $updatedData = [
            'name' => 'Existing Class 1',
            'academicYear' => '2024-2025',
        ];
        $response = $this->putJson('/api/v1/classes/' . $class2->id, $updatedData);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }
}
