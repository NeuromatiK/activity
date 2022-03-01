<?php

namespace App\CQRS\QueryBus;

use App\CQRS\Query\Query;

interface QueryBus
{
    public function handle(Query $query);
}
