<?php

namespace App\CQRS\Factory;

use JetBrains\PhpStorm\ArrayShape;

class ResponseFactory implements Factory
{
    public const   METHOD_NOT_FOUND = -32601;
    public const INVALID_PARAMETERS = -32602;
    public const PARSE_ERROR = -32700;
    public const INVALID_REQUEST = -32600;

    private const ERROR_MESSAGES = [
        '-32601' => 'Method not found',
        '-32602' => 'Invalid arguments'
    ];

    #[ArrayShape(['json-rpc' => "string", 'error' => "array", 'id' => "?int"])]
    public static function makeErrorResponse(int $code, $id = null): array
    {
        $message = self::ERROR_MESSAGES[$code] ?? '';
        $result = [
            'json-rpc' => '2.0',
            'error' => ['code' => $code, 'message' => $message]
        ];
        if ($id) {
            $result['id'] = $id;
        }
        return $result;
    }

    #[ArrayShape(['json-rpc' => "string", 'result' => "", 'id' => "int"])]
    public static function makeValidResponse($result, $id): array
    {
        return [
            'json-rpc' => '2.0',
            'result' => $result,
            'id' => $id
        ];
    }
}
