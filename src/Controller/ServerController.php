<?php

namespace App\Controller;

use App\Enum\ExcelServerFields;
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
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => ['16', '32'],
            ExcelServerFields::HDD_CAPACITY => '0-1024',
        ];

        $filePath = $this->getParameter('kernel.project_dir').'/var/data/servers_filters_assignment.xlsx';

        $serverRepo = (new ExcelServerRepository($filePath, new ExcelFilterMatcher(), new StorageParser(),
            new RamParser()))
            ->orderBy('location', 'desc')
            ->setFilters($filters);

        $servers = $serverRepo->getServers();
        return new JsonResponse([
            'servers' => $servers,
        ]);
    }
}
