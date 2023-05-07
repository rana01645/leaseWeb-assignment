<?php

namespace App\Repository;

interface ServerRepositoryInterface
{
    public function setFilters(array $filters): self;

    public function setCurrentPage(int $page): self;

    public function setPerPage(int $perPage): self;

    public function getCurrentPage(): int;

    public function getNextPage(): int;

    public function getPreviousPage(): int;

    public function getServers(): array;
}
