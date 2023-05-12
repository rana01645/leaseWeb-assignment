<?php

namespace App\Controller;

use App\Repository\ServerRepositoryInterface;
use App\Service\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/server', name: 'app_server')]
    public function index(ServerRepositoryInterface $repository,FilterService $service, Request $request): JsonResponse
    {
        $filters = $service->generateFilter($request);
        $serverRepo = $repository
            ->orderBy('location', 'desc')
            ->setFilters($filters);

        $servers = $serverRepo->getServers();
        $locations = $serverRepo->getLocations();
        $ramOptions = $serverRepo->getRamOptions();

        return new JsonResponse([
            'servers' => $servers,
            'locations' => $locations,
            'ramOptions' => $ramOptions,
        ]);
    }

}
