<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Services\CacheKeyService;
use App\Services\CacheService;
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
    private CacheService $cacheService;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->cacheService = new CacheService();

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

        $cachedValue = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNotNull($cachedValue);

        $this->product->delete();

        $response2 = $this->getJson('/api/products');
        $response2->assertStatus(200);

        $this->assertEquals($response1->json(), $response2->json());
    }

    /**
     * Тест проверяет инвалидацию кэша после создания товара
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

        $cachedValue = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNotNull($cachedValue);

        $newProduct = [
            'name' => 'New Test Product',
            'price' => 200,
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/products', $newProduct, [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(201);

        $cachedValueAfter = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNull($cachedValueAfter);
    }

    /**
     * Тест проверяет инвалидацию кэша после обновления товара
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

        $cachedValue = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNotNull($cachedValue);

        $updateData = [
            'name' => 'Updated Product Name',
            'price' => 100,
            'category_id' => $this->category->id,
        ];

        $response = $this->putJson("/api/products/{$this->product->id}", $updateData, [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(200);

        $cachedValueAfter = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNull($cachedValueAfter);
    }

    /**
     * Тест проверяет инвалидацию кэша после удаления товара
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

        $cachedValue = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNotNull($cachedValue);

        $response = $this->deleteJson("/api/products/{$this->product->id}", [], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(200);

        $cachedValueAfter = $this->cacheService->remember($cacheKey, 300, fn() => null, 'products');
        $this->assertNull($cachedValueAfter);
    }
}
