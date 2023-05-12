<?php

namespace App\Repository;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem;

class CachedExcelServerRepository implements ServerRepositoryInterface
{
    private ServerRepositoryInterface $repository;
    private FilesystemAdapter $cache;
    private Filesystem $filesystem;
    private string $filePath;

    public function __construct(ServerRepositoryInterface $repository, FilesystemAdapter $cache, string $filePath)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->filesystem = new Filesystem();
        $this->filePath = $filePath;
    }

    private function getLastModified(): int
    {
        $lastModified = $this->cache->getItem('last_modified');
        if (!$lastModified->isHit()) {
            $lastModified->set(0);
            $this->cache->save($lastModified);
        }
        return $lastModified->get();
    }

    private function setLastModified(int $lastModified): void
    {
        $lastModifiedItem = $this->cache->getItem('last_modified');
        $lastModifiedItem->set($lastModified);
        $this->cache->save($lastModifiedItem);
    }

    private function isModified(): bool
    {
        if (!$this->filesystem->exists($this->filePath)) {
            return true;
        }

        $modified = filemtime($this->filePath);
        return $modified > $this->getLastModified();
    }

    private function updateLastModified(): void
    {
        $lastModified = filemtime($this->filePath);
        $this->setLastModified($lastModified);
    }

    public function getCachedFilteredKey(): string
    {
        return 'filtered_servers_'.md5(serialize($this->getFilters()));
    }

    public function getCachedServers(): array
    {
        $cacheKey = 'servers';
        if ($this->hasFilters()) {
            $cacheKey = $this->getCachedFilteredKey();
        }

        $cachedData = $this->cache->getItem($cacheKey);
        if ($cachedData->isHit() && !$this->isModified()) {
            return $cachedData->get();
        }

        $servers = $this->repository->getServers();

        $cachedData->set($servers);
        $this->cache->save($cachedData);
        $this->updateLastModified();

        return $servers;
    }

    public function getCachedLocations(): array
    {
        $cacheKey = 'locations';

        $cachedData = $this->cache->getItem($cacheKey);
        if ($cachedData->isHit() && !$this->isModified()) {
            return $cachedData->get();
        }

        $locations = $this->repository->getLocations();

        $cachedData->set($locations);
        $this->cache->save($cachedData);
        $this->updateLastModified();

        return $locations;
    }

    public function getCachedRamOptions(): array
    {
        $cacheKey = 'ram_options';

        $cachedData = $this->cache->getItem($cacheKey);
        if ($cachedData->isHit() && !$this->isModified()) {
            return $cachedData->get();
        }

        $ramOptions = $this->repository->getRamOptions();

        $cachedData->set($ramOptions);
        $this->cache->save($cachedData);
        $this->updateLastModified();

        return $ramOptions;
    }

    public function getCachedOrderedServers(string $orderByField = '', string $orderByDirection = 'asc'): array
    {
        $cacheKey = 'ordered_servers_'.$orderByField.'_'.$orderByDirection;

        $cachedData = $this->cache->getItem($cacheKey);
        if ($cachedData->isHit() && !$this->isModified()) {
            return $cachedData->get();
        }
        $orderedServers = $this->repository->getServers($orderByField, $orderByDirection);

        $cachedData->set($orderedServers);
        $this->cache->save($cachedData);
        $this->updateLastModified();

        return $orderedServers;
    }

    public function setFilters(array $filters): ServerRepositoryInterface
    {
        $this->repository->setFilters($filters);
        return $this;
    }

    public function getServers(): array
    {
        return $this->getCachedServers();
    }

    public function getLocations(): array
    {
        return $this->getCachedLocations();
    }

    public function getRamOptions(): array
    {
        return $this->getCachedRamOptions();
    }

    public function orderBy(string $field, string $direction = 'asc'): ServerRepositoryInterface
    {
       $this->repository->orderBy($field, $direction);
       return $this;
    }

    public function hasFilters(): bool
    {
        return $this->repository->hasFilters();
    }

    public function getFilters(): array
    {
        return $this->repository->getFilters();
    }
}




