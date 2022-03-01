<?php

namespace App\CQRS\Query;

class VisitList implements Query
{
    private const DEFAULT_RECORDS_PER_PAGE = 10;
    private array $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function getPage()
    {
        return $this->args['page'];
    }

    public function getRecordsPerPage()
    {
        return $this->args['rpp'] ?? self::DEFAULT_RECORDS_PER_PAGE;
    }

    public function isValid(): bool
    {
        return true; // cause we can use default arguments. page=0, rpp=10
    }
}
