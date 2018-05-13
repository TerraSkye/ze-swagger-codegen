<?php

declare(strict_types=1);

namespace Swagger\Middleware;

use Zend\Hydrator\HydratorInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Validator\ValidatorChain;
use Zend\Diactoros\Response\JsonResponse;

class ModelMiddleware implements MiddlewareInterface
{
    /**
     * @var object
     */
    protected $model;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var ValidatorChain[]
     */
    protected $validatorChains;

    /**
     * Constructor
     * ---
     * @param object $model
     * @param HydratorInterface $hydrator
     * @param ValidatorChain[] $validatorChains
     */
    public function __construct($model, HydratorInterface $hydrator, array $validatorChains)
    {
        $this->model = $model;
        $this->hydrator = $hydrator;
        $this->validatorChains = $validatorChains;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $model = $this->hydrator->hydrate($request->getParsedBody(), $this->model);

        $messages = [];
        foreach ($this->validatorChains as $propertyName => $validatorChain) {
            /** @var ValidatorChain $validatorChain **/
            if (!$validatorChain->isValid($request->getParsedBody()[$propertyName]??null) ) {
                $messages[$propertyName] = array_values($validatorChain->getMessages());
            }
        }

        if (!empty($messages)) {
            return new JsonResponse($messages, 400);
        }

        return $handler->handle($request->withAttribute('Model', $model));
    }
}
