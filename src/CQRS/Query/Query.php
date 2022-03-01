<?php

namespace App\CQRS\Query;

interface Query
{
    public function isValid(): bool;
}
