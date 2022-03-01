<?php

namespace App\CQRS\Command;

class VisitStore implements Command
{
    private array $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function isValid(): bool
    {
        return isset($this->args['url'], $this->args['date']);
    }

    public function getUrl()
    {
        return $this->args['url'];
    }

    public function getDate()
    {
        return $this->args['date'];
    }
}
