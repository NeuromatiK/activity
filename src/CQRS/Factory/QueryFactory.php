<?php

namespace App\CQRS\Factory;

use App\CQRS\Exception\CommandNotFoundException;
use App\CQRS\Exception\InvalidArgumentsException;
use App\CQRS\Query\Query;

class QueryFactory implements Factory
{
    /**
     * @throws CommandNotFoundException
     * @throws InvalidArgumentsException
     */
    public static function createQueryFromString(string $sQueryName, array $args, int $id): Query
    {
        $classNameParts = preg_split('/(_|\.)/', $sQueryName);
        $classNameParts = array_map(static function ($parts) {
            return ucfirst(strtolower($parts));
        }, $classNameParts);
        $className = '\\App\\CQRS\\Query\\' . implode('', $classNameParts);
        if (class_exists($className)) {
            $oCommand = new $className($args);
            if ($oCommand->isValid()) {
                return $oCommand;
            }
            throw new InvalidArgumentsException();
        }
        throw new CommandNotFoundException();
    }
}
