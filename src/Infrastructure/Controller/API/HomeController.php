<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\API;

use App\Application\Service\GetTwilioAccessToken\GetTwilioAccessTokenCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractAPIController
{
    #[Route('/api', name: 'api_home', methods: ['GET'])]
    public function home(Request $request): Response
    {
        return new JsonResponse();
    }

}
