<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\RateLimiter;

abstract class Controller
{
    /**
     * Проверка превышения лимита запросов для метод
     *
     * @param string $methodName Название метода
     * @param array $keys Массив ключей
     * @param int $maxAttempts Максимальное количество попыток за период
     * @param int $decaySeconds Время действия лимита в секундах
     *
     * @return array|null Массив с ошибкой при превышении лимита
     */
    protected function checkRateLimit(
        string $methodName,
        array  $keys,
        int    $maxAttempts,
        int    $decaySeconds
    ): ?array
    {
        foreach ($keys as $key) {
            $fullKey = 'rate_limit:' . class_basename(static::class) . ':' . $methodName . ':' . $key;

            if (RateLimiter::tooManyAttempts($fullKey, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($fullKey);

                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'There are too many requests. Try again later',
                    'error_code' => 'RATE_LIMIT_EXCEEDED',
                    'http_status' => 429,
                    'retry_after' => $seconds,
                ];
            }
        }

        foreach ($keys as $key) {
            $fullKey = 'rate_limit:' . class_basename(static::class) . ':' . $methodName . ':' . $key;
            RateLimiter::hit($fullKey, $decaySeconds);
        }
        return null;
    }
}
