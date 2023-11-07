<?php declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Infrastructure\Exceptions\HandlerApiExceptions;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{

    private HandlerApiExceptions $handlerApiExceptions;
    private SerializerInterface $serializer;
    private string $env;

    public function __construct(
        HandlerApiExceptions $handlerApiExceptions,
        SerializerInterface $serializer,
        string $env = "prod"
    )
    {
        $this->handlerApiExceptions = $handlerApiExceptions;
        $this->serializer = $serializer;
        $this->env = $env;
    }

    /**
     * @return array<string, array<int, int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', -1],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($this->env != "prod") {
            return;
        }
        //dd($event);
        //$path = $event->getRequest()->getPathInfo();

        $apiResponse = new JsonResponse();

        $result = $this->handlerApiExceptions->byClassName(get_class($event->getThrowable()));
        $result['description'] = !empty($result['description']) ? $result['description'] : $event->getThrowable()->getMessage();

        $apiResponse->setStatusCode($result['status']);
        $apiResponse->setContent($this->serializer->serialize($result, 'json'));
        $apiResponse->headers->set('Content-Type', 'application/json');
        $event->setResponse($apiResponse);
    }
}