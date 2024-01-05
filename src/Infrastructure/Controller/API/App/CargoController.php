<?php

namespace App\Infrastructure\Controller\API\App;

use App\Application\Service\Cargo\AddCargoCommand;
use App\Infrastructure\Annotations\ApiAnnotation;
use App\Infrastructure\Controller\API\AbstractAPIController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes\Schema;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CargoController extends AbstractAPIController
{
    #[Route(path:"/api/app/cargo/add", name: "app_cargo", methods: ["POST"]), ApiAnnotation(secure: true)]
    public function new(): Response
    {
        return $this->command->handle(
            new AddCargoCommand(
                id: Uuid::uuid4(),
                name: $this->params->get('name')
            )
        );
    }
}