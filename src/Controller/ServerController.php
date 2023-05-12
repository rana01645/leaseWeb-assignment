<?php

namespace App\Controller;

use App\Repository\ServerRepositoryInterface;
use App\Service\FilterService;
use App\Service\OrderByService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/server', name: 'app_server')]
    public function index(ServerRepositoryInterface $repository,FilterService $service,OrderByService $orderByService, Request $request): JsonResponse
    {
        $filters = $service->generateFilter($request);
        $orderBy = $orderByService->getOrderByField($request);
        $orderByDirection = $orderByService->getOrderByDirection($request);

        $serverRepo = $repository
            ->setFilters($filters);

        if ($orderBy && $orderByDirection) {
            $serverRepo->orderBy($orderBy, $orderByDirection);
        }

        return new JsonResponse([
            'servers' => $serverRepo->getServers(),
            'locations' => $serverRepo->getLocations(),
            'ramOptions' => $serverRepo->getRamOptions(),
        ]);
    }

}
