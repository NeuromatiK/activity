<?php

namespace App\Service\JsonRPC;

use JetBrains\PhpStorm\ArrayShape;

class Parser
{
    public static function parseJson($json): ?array
    {
        try {
            $rpc = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return null;
        }

        $parsedCalls = [];
        if (isset($rpc['method'])) { // batch or single request
            $rpc = [$rpc];
        }
        foreach ($rpc as $singleCall) {
            $parsedCalls[] = static::_parseCall($singleCall);
        }

        return $parsedCalls;
    }

    #[ArrayShape(['method' => 'string', 'type' => 'string', 'args' => 'array', 'id' => '?int'])]
    private static function _parseCall($call): ?array
    {
        $id = $call['id'] ?? null; // by specs - can't be null, but this is test task, just ignore. sorry :)
        $type = $id ? 'query' : 'command';
        $args = $call['params'] ?? [];
        $method = $call['method'] ?? null;

        $result = ['method' => $method, 'type' => $type, 'args' => $args];
        if ($id) {
            $result['id'] = $id;
        }

        return $result;
    }
}
