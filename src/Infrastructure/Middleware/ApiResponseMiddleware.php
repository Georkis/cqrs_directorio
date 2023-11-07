<?php declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Annotations\ApiAnnotationListener;
use App\Infrastructure\Exceptions\HandlerApiExceptions;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use League\Tactician\Middleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware implements Middleware
{

    private JsonResponse $apiResponse;
    private HandlerApiExceptions $handlerApiExceptions;
    private SerializerInterface $serializer;
    private string $env;
    private ApiAnnotationListener $apiAnnotationListener;

    public function __construct(
        HandlerApiExceptions $handlerApiExceptions,
        SerializerInterface $serializer,
        ApiAnnotationListener $apiAnnotationListener,
        string $env = "prod"
    )
    {
        $this->apiResponse = new JsonResponse('', Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
        $this->handlerApiExceptions = $handlerApiExceptions;
        $this->serializer = $serializer;
        $this->env = $env;
        $this->apiAnnotationListener = $apiAnnotationListener;
    }

    public function execute($command, callable $next): JsonResponse
    {
        try {
            $returnValue = $next($command);

            $this->apiResponse->setStatusCode(Response::HTTP_OK);

            $context = SerializationContext::create()->enableMaxDepthChecks();

            $context->setGroups(array_merge(["default"], $this->apiAnnotationListener->groups()));

            //dd($this->serializer->serialize($returnValue, 'json', $context));

            if (!empty($returnValue)) {
                if (is_string($returnValue)) {
                    $this->apiResponse->setContent($returnValue);
                } else {
                    //dd($returnValue);
                    //
                    //$serializedObjects = $this->serializer->serialize($returnValue, 'json', $context);
                    //
                    //if (is_array($returnValue)) {
                    //    $values = [];
                    //    foreach ($returnValue as $k => $r) {
                    //        if (!is_object($r)) {
                    //            $values[$k] = $r;
                    //        }
                    //    }
                    //
                    //    if(count($values) > 0){
                    //        $objects = json_decode($serializedObjects);
                    //        foreach ($values as $k => $v) {
                    //            $objects->$k = $v;
                    //        }
                    //        dd($objects);
                    //    }
                    //}

                    $this->apiResponse->setContent($this->serializer->serialize($returnValue, 'json', $context));
                }
            } else {
                if (is_array($returnValue)) {
                    $this->apiResponse->setContent("[]");
                } else {
                    $this->apiResponse->setContent("{}");
                }
            }

        } catch (\Exception $e) {

            $result = $this->handlerApiExceptions->handle($e);

            if ($this->env != "prod") {
                if ($result['status'] == Response::HTTP_INTERNAL_SERVER_ERROR) {
                    throw $e;
                }
            }

            $this->apiResponse->setStatusCode($result['status']);
            $this->apiResponse->setContent($this->serializer->serialize($result, 'json'));
        }

        return $this->apiResponse;
    }
}