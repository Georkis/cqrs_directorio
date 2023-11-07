<?php declare(strict_types=1);

namespace App\Infrastructure\Annotations;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiAnnotationListener implements EventSubscriberInterface
{
    private bool $secure = false;
    private array $groups = [];

    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $controllers = $event->getController();

        if (!is_array($controllers)) {
            return;
        }

        $this->handleAnnotation($controllers);

    }

    /**
     * @param mixed[] $controllers
     */
    private function handleAnnotation(array $controllers): void
    {
        list($controller, $method) = $controllers;

        try {
            $controller = new ReflectionClass($controller);
            $this->handleMethodAnnotation($controller, $method);
        } catch (ReflectionException $e) {
            throw new RuntimeException('Failed to read annotation!');
        }
    }

    /**
     * @param ReflectionClass $controller
     * @param string $method
     * @throws ReflectionException
     */
    private function handleMethodAnnotation(ReflectionClass $controller, string $method): void
    {
        $method = $controller->getMethod($method);

        $attributes = $method->getAttributes(ApiAnnotation::class);

        foreach ($attributes as $attribute) {
            /** @var ApiAnnotation $listener */
            $listener = $attribute->newInstance();
            $this->secure = $listener->secure();
            $this->groups = $listener->groups();
        }
    }

    public function secure(): bool
    {
        return $this->secure;
    }

    public function groups(): array
    {
        return $this->groups;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
