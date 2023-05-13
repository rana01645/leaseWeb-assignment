<?php

namespace App\Repository;

interface ServerRepositoryInterface
{
    public function getServers(): array;
    public function getLocations(): array;
    public function getRamOptions(): array;
}
