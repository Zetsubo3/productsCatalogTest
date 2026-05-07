<?php

namespace App\Services;

class MainService
{
    /**
     * Форматирует ответ в стандартную структуру
     *
     * @param bool $success Успешность операции
     * @param mixed|null $data Данные ответа
     * @param string $message Сообщение
     * @param string|null $errorCode Код ошибки (если есть)
     * @param int $httpStatus HTTP статус код
     * @return array
     */
    protected function formatResponse(
        bool    $success,
        mixed   $data = null,
        string  $message = '',
        ?string $errorCode = null,
        int     $httpStatus = 200
    ): array {
        return [
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'error_code' => $errorCode,
            'http_status' => $httpStatus
        ];
    }
}
