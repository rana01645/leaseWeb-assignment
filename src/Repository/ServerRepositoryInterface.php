<?php

namespace App\Repository;

interface ServerRepositoryInterface
{
    public function setFilters(array $filters): self;

    public function orderBy(string $column, string $order): self;

    public function getServers(): array;
}
