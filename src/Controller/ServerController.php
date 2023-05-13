<?php

namespace App\Controller;

use App\Repository\ServerRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/server', name: 'app_server')]
    public function index(ServerRepositoryInterface $repository): JsonResponse
    {
        return new JsonResponse([
            'servers' => $repository->getServers(),
            'locations' => $repository->getLocations(),
            'ramOptions' => $repository->getRamOptions(),
        ]);
    }

}
