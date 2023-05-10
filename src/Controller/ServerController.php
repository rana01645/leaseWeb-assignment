<?php

namespace App\Controller;

use App\Enum\ExcelServerFields;
use App\Repository\ServerRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/server', name: 'app_server')]
    public function index(ServerRepositoryInterface $repository): JsonResponse
    {

        // Define the filters you want to apply
        $filters = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => ['16', '32'],
            ExcelServerFields::HDD_CAPACITY => '0-1024',
        ];

        $serverRepo = $repository
            ->orderBy('location', 'desc')
            ->setFilters($filters);

        $servers = $serverRepo->getServers();
        $locations = $serverRepo->getLocations();
        $ramOptions = $serverRepo->getRamOptions();
        return new JsonResponse(
            [
                'servers' => $servers,
                'locations' => $locations,
                'ramOptions' => $ramOptions,
            ]
        );
    }
}
