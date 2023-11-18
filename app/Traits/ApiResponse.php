<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success(array|object $data = [], string $message = '', int $code = 200): array
    {
        return $this->res('success', $message, $data, $code);
    }

    protected function failed(array|object $data = [], string $message = '', int $code = 204): array
    {
        return $this->res('failed', $message, $data, $code);
    }

    protected function error(array|object $data = [], string $message = '', int $code = 400): array
    {
        return $this->res('Invalid request', $message, $data, $code);
    }

    protected function notFound(array|object $data = [], string $message = '', int $code = 404): array
    {
        return $this->res('not_found', $message, $data, $code);
    }

    protected function res(string $type, string $message, array|object $data, int $code): array
    {
        if ($type === 'success') {
            return [
                'response' => true,
                'type'     => $type,
                'message'  => $message,
                'status'   => $code,
                'data'     => $data,
            ];
        } else {
            return [
                'response' => false,
                'type'     => $type,
                'message'  => $message,
                'status'   => $code,
                'data'     => $data,
            ];
        }
    }
}
