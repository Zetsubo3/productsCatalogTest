<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Services\CacheKeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductCacheTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private Product $product;
    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'price' => 100,
        ]);

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Тест проверяет, что ответ каталога товаров кэшируется
     *
     * 1. Делает первый запрос к /api/products - ответ сохраняется в кэш
     * 2. Удаляет товар из базы данных
     * 3. Делает второй запрос — ответ должен быть таким же (из кэша)
     *
     * @return void
     */
    public function test_caches_products_index_response(): void
    {
        $cacheKey = (new CacheKeyService())->productsIndexKey([
            'page' => 1,
            'count' => 15,
        ]);

        $response1 = $this->getJson('/api/products');
        $response1->assertStatus(200);

        $this->assertNotNull(Cache::get($cacheKey));

        $this->product->delete();

        $response2 = $this->getJson('/api/products');
        $response2->assertStatus(200);

        $this->assertEquals($response1->json(), $response2->json());
    }

    /**
     * Тест проверяет инвалидацию кэша после создания товара
     *
     * 1. Делает запрос к /api/products - наполняет кэш
     * 2. Создаёт новый товар через POST /api/products
     * 3. Проверяет, что кэш очищен
     *
     * @return void
     */
    public function test_invalidates_cache_after_product_creation(): void
    {
        $cacheKey = (new CacheKeyService())->productsIndexKey([
            'page' => 1,
            'count' => 15,
        ]);

        $this->getJson('/api/products');
        $this->assertNotNull(Cache::get($cacheKey));

        $newProduct = [
            'name' => 'New Test Product',
            'price' => 200,
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/products', $newProduct, [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(201);

        $this->assertNull(Cache::get($cacheKey));
    }

    /**
     * Тест проверяет инвалидацию кэша после обновления товара
     *
     * 1. Делает запрос к /api/products - наполняет кэш
     * 2. Обновляет товар через PUT /api/products/{id}
     * 3. Проверяет, что кэш очищен
     *
     * @return void
     */
    public function test_invalidates_cache_after_product_update(): void
    {
        $cacheKey = (new CacheKeyService())->productsIndexKey([
            'page' => 1,
            'count' => 15,
        ]);

        $this->getJson('/api/products');
        $this->assertNotNull(Cache::get($cacheKey));

        $updateData = [
            'name' => 'Updated Product Name',
            'price' => 100,
            'category_id' => $this->category->id,
        ];

        $response = $this->putJson("/api/products/{$this->product->id}", $updateData, [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(200);

        $this->assertNull(Cache::get($cacheKey));
    }

    /**
     * Тест проверяет инвалидацию кэша после удаления товара
     *
     * 1. Делает запрос к /api/products - наполняет кэш
     * 2. Удаляет товар через DELETE /api/products/{id}
     * 3. Проверяет, что кэш очищен
     *
     * @return void
     */
    public function test_invalidates_cache_after_product_deletion(): void
    {
        $cacheKey = (new CacheKeyService())->productsIndexKey([
            'page' => 1,
            'count' => 15,
        ]);

        $this->getJson('/api/products');
        $this->assertNotNull(Cache::get($cacheKey));

        $response = $this->deleteJson("/api/products/{$this->product->id}", [], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(200);

        $this->assertNull(Cache::get($cacheKey));
    }
}
