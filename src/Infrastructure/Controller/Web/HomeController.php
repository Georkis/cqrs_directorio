<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\Web;

use App\Application\Service\GetTwilioAccessToken\GetTwilioAccessTokenCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use const _PHPStan_1623582d5\__;

class HomeController extends AbstractWebController
{
    #[Route('/', name: 'web_home', methods: ['GET'])]
    public function home(Request $request): Response
    {
        return $this->render("home.twig");
    }

}
