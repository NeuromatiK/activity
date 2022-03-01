<?php

namespace App\CQRS\Factory;

use App\CQRS\Command\Command;
use App\CQRS\Exception\CommandNotFoundException;
use App\CQRS\Exception\InvalidArgumentsException;

class CommandFactory implements Factory
{
    /**
     * @throws CommandNotFoundException
     * @throws InvalidArgumentsException
     */
    public static function createCommandFromString(string $sCommandName, array $args): Command
    {
        $classNameParts = preg_split('/(_|\.)/', $sCommandName);
        $classNameParts = array_map(static function ($parts) {
            return ucfirst(strtolower($parts));
        }, $classNameParts);
        $className = '\\App\\CQRS\\Command\\' . implode('', $classNameParts);
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
