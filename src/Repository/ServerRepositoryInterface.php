<?php

namespace App\Repository;

interface ServerRepositoryInterface
{
    public function setFilters(array $filters): self;
    public function getServers(): array;
}
