<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function products_can_be_retrieved()
    {
        $user = User::factory()->create();
        Product::create([
            'name' => 'Sample Product',
            'slug' => 'sample-product',
            'description' => 'Sample description',
            'price' => 100,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/products');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'slug', 'description', 'price']
                 ]);
    }

    /** @test */
    public function a_product_can_be_created()
    {
        $user = User::factory()->create();
        $productData = [
            'name' => 'Test Product',
            'slug' => $this->faker->unique()->slug,
            'description' => 'Test Description',
            'price' => $this->faker->numberBetween(100, 1000),
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/products', $productData);
        $response->assertStatus(201)
                 ->assertJsonPath('name', $productData['name'])
                 ->assertJsonPath('slug', $productData['slug']);
        $this->assertDatabaseHas('products', ['name' => $productData['name']]);
    }

    /** @test */
    public function a_product_can_be_retrieved()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Existing Product',
            'slug' => 'existing-product',
            'description' => 'Existing description',
            'price' => 150,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/products/{$product->id}");
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => 'Existing Product',
                 ]);
    }

    /** @test */
    public function a_product_can_be_updated()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Initial Product',
            'slug' => 'initial-product',
            'description' => 'Initial Description',
            'price' => 200,
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'slug' => 'updated-product',
            'description' => 'Updated Description',
            'price' => 250,
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/products/{$product->id}", $updateData);
        $response->assertStatus(200)
                 ->assertJsonPath('name', $updateData['name'])
                 ->assertJsonPath('slug', $updateData['slug']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => $updateData['name']]);
    }

    /** @test */
    public function a_product_can_be_deleted()
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Delete Product',
            'slug' => 'delete-product',
            'description' => 'Delete Description',
            'price' => 300,
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Product deleted successfully']);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
