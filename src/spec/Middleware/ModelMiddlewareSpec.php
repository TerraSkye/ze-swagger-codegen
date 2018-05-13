<?php

namespace spec\Swagger\Middleware;

use Zend\Hydrator\HydratorInterface;
use Swagger\Middleware\ModelMiddleware;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use App\Model\Test;
use Zend\Validator\ValidatorChain;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ModelMiddlewareSpec extends ObjectBehavior
{
    public function let(
        Test $testModel,
        HydratorInterface $hydrator,
        ValidatorChain $validatorChain
    ) {
        $this->beConstructedWith($testModel, $hydrator, [$validatorChain]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ModelMiddleware::class);
    }

    public function it_is_middlware()
    {
        $this->shouldImplement(MiddlewareInterface::class);
    }

    public function it_can_process(
        Test $testModel,
        HydratorInterface $hydrator,
        ValidatorChain $validatorChain,
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        ResponseInterface $response
    ) {
        $request->getParsedBody()->willReturn([]);
        $hydrator->hydrate(Argument::type('array'), $testModel)->willReturn($testModel);
        $validatorChain->isValid(Argument::any())->willReturn(true);

        $handler->handle($request)->willReturn($response);
        $handler->handle($request)->shouldBeCalled();

        $request->withAttribute('Model', $testModel)->willReturn($request);

        $this->process($request, $handler)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }

    public function it_can_process_with_validation_errors(
        Test $testModel,
        HydratorInterface $hydrator,
        ValidatorChain $validatorChain,
        ServerRequestInterface $request,
        RequestHandlerInterface $handler        
    ) {
        $request->getParsedBody()->willReturn([]);
        $hydrator->hydrate(Argument::type('array'), $testModel)->willReturn($testModel);
        $validatorChain->isValid(Argument::any())->willReturn(false);
        $validatorChain->getMessages()->willReturn([]);
        $validatorChain->getMessages()->shouldBeCalled();

        $request->withAttribute('Model', $testModel)->willReturn($request);

        $this->process($request, $handler)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }
}
