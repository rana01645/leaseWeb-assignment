<?php

namespace App\Controller;

use App\Repository\ExcelServerRepository;
use App\Service\ExcelFilterMatcher;
use App\Utils\RamParser;
use App\Utils\StorageParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/server', name: 'app_server')]
    public function index(): JsonResponse
    {

        // Define the filters you want to apply
        $filters = [
            'location' => 'Amsterdam',
            'hdd_types' => 'SSD',
            'ram_capacity' => ['16', '32'],
            'hdd_capacity' => '0-1024',
        ];

        $filePath = $this->getParameter('kernel.project_dir').'/var/data/servers_filters_assignment.xlsx';

        $serverRepo = (new ExcelServerRepository($filePath, new ExcelFilterMatcher(), new StorageParser(),
            new RamParser()))
            ->orderBy('price', 'asc')
            ->setFilters($filters);

        $servers = $serverRepo->getServers();
        return new JsonResponse([
            'servers' => $servers,
        ]);
    }
}
