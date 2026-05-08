<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Получить данные из кэша или сохранить через callback
     * При передаче тега используется кэширование с тегами (для драйверов, поддерживающих теги)
     *
     * @param string $key Ключ кэша
     * @param int $ttl Время жизни в секундах
     * @param callable $callback Функция, возвращающая данные для сохранения
     * @param string|null $tag Тег для групповой инвалидации
     * @return mixed
     */
    public function remember(string $key, int $ttl, callable $callback, ?string $tag = null): mixed
    {
        if ($tag !== null && Cache::supportsTags()) {
            return Cache::tags([$tag])->remember($key, $ttl, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Очистить кэш
     * При передаче тега удаляются только ключи с этим тегом (для драйверов, поддерживающих теги)
     *
     * @param string|null $tag Тег для групповой инвалидации
     * @return void
     */
    public function forget(?string $tag = null): void
    {
        if ($tag !== null && Cache::supportsTags()) {
            Cache::tags([$tag])->flush();
        } else {
            Cache::flush();
        }
    }
}
