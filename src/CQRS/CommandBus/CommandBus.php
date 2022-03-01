<?php

namespace App\CQRS\CommandBus;

use App\CQRS\Command\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;

}
