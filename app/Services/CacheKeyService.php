<?php

namespace App\Services;

class CacheKeyService
{
    /**
     * Генерация ключа для списка товаров
     *
     * @param array $params
     * @return string
     */
    public function productsIndexKey(array $params): string
    {
        // сортируем параметры для ключа
        ksort($params);

        // базовый префикс
        $parts = ['products'];

        // добавляем только не пустые параметры
        foreach ($params as $key => $value) {
            if ($value !== null && $value !== '') {
                $parts[] = $key . ':' . $value;
            }
        }

        return implode('|', $parts);
    }
}
