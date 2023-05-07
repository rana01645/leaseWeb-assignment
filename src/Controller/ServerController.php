<?php

namespace App\Controller;

use App\Repository\ExcelServerRepository;
use App\Service\ExcelFilterMatcher;
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


        ];

        $filePath = $this->getParameter('kernel.project_dir').'/var/data/servers_filters_assignment.xlsx';

        $serverRepo = (new ExcelServerRepository($filePath,new ExcelFilterMatcher(), new StorageParser()))
                    ->setFilters($filters)
                    ->setCurrentPage(1)
                    ->setPerPage(400);

        $servers = $serverRepo->getServers();
        return new JsonResponse([
            'currentPage' => $serverRepo->getCurrentPage(),
            'nextPage' => $serverRepo->getNextPage(),
            'servers' => $servers,
        ]);
    }
}
