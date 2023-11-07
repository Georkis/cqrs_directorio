<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\API;

use App\Domain\Manager\TokenManager;
use App\Infrastructure\Controller\Params;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractAPIController extends AbstractController
{
    protected CommandBus $command;
    protected CommandBus $query;
    protected Params $params;
    protected TokenManager $tokenManager;

    public function __construct(
        CommandBus $command,
        CommandBus $query,
        RequestStack $requestStack,
        TokenManager $tokenManager
    )
    {
        $this->command = $command;
        $this->query = $query;
        $this->params = $this->parseData($requestStack->getCurrentRequest());
        $this->tokenManager = $tokenManager;
    }

    protected function parseData(Request $request): Params
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            if (!$params = json_decode($content, true)) {
                $params = [];
            }
        }

        return new Params(
            array_merge(
                $request->query->all(),
                $request->request->all(),
                $params
            )
        );
    }
}