<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Tour;
use App\Models\Category;

class ToursControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_a_new_tour()
    {
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'name' => 'Amazing Tour',
            'title' => 'Amazing Tour Title',
            'description' => 'This is a description of the amazing tour.',
            'itinerary' => 'Day 1: Arrival, Day 2: Sightseeing, Day 3: Departure',
            'duration' => '3 days',
            'price' => 299.99,
            'main_image_url' => 'https://example.com/tour-image.jpg',
            'status' => 'active',
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-03',
            'max_travelers' => 20,
            'difficulty_level' => 'moderate',
        ];

        $response = $this->postJson('/api/tours', $data);

        $response->assertStatus(201)
                 ->assertJson($data);

        $this->assertDatabaseHas('tours', $data);
    }

    /** @test */
    public function it_can_store_a_new_tour_with_image_upload()
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'name' => 'Amazing Tour',
            'title' => 'Amazing Tour Title',
            'description' => 'This is a description of the amazing tour.',
            'itinerary' => 'Day 1: Arrival, Day 2: Sightseeing, Day 3: Departure',
            'duration' => '3 days',
            'price' => 299.99,
            'main_image' => UploadedFile::fake()->image('tour.jpg'),
            'status' => 'active',
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-03',
            'max_travelers' => 20,
            'difficulty_level' => 'moderate',
        ];

        $response = $this->postJson('/api/tours', $data);

        $response->assertStatus(201);

        $tour = Tour::first();
        $this->assertNotNull($tour->main_image_url);
        Storage::disk('public')->assertExists($tour->main_image_url);
    }

    /** @test */
    public function it_requires_valid_data_to_store_a_tour()
    {
        $response = $this->postJson('/api/tours', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'category_id',
                     'name',
                     'status',
                 ]);
    }

    /** @test */
    public function it_requires_existing_category()
    {
        $data = [
            'category_id' => 999, // non-existent
            'name' => 'Amazing Tour',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/tours', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'category_id',
                 ]);
    }
}
