<?php

namespace App\CQRS\Command;

interface Command
{
    public function isValid(): bool;
}
