<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\Web;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractWebController
{
    #[Route('/reset-password/', name: 'web_reset_password', methods: ['GET'])]
    public function get(): Response
    {
        return $this->render("reset-password.twig", [
            "token" => $this->params->get("token")
        ]);
    }
}
