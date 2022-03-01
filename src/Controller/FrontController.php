<?php

namespace App\Controller;

use App\CQRS\CommandBus\CommandBus;
use App\CQRS\Exception\CommandNotFoundException;
use App\CQRS\Exception\InvalidArgumentsException;
use App\CQRS\Factory\CommandFactory;
use App\CQRS\Factory\QueryFactory;
use App\CQRS\Factory\ResponseCode;
use App\CQRS\Factory\ResponseFactory;
use App\CQRS\Factory\ResponseMessage;
use App\CQRS\QueryBus\QueryBus;
use App\Service\JsonRPC\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route("/", name: "front", methods: 'POST')]
    public function index(Request $request, QueryBus $queryBus, CommandBus $commandBus): JsonResponse
    {
        $body = $request->getContent();
        if (!$body) {
            return new JsonResponse(ResponseFactory::makeErrorResponse(ResponseFactory::PARSE_ERROR));
        }

        $calls = Parser::parseJson($body);
        if (empty($calls)) {
            return new JsonResponse(ResponseFactory::makeErrorResponse(ResponseFactory::INVALID_REQUEST));
        }

        $requestsResult = [];
        foreach ($calls as $call) {
            switch ($call['type']) {
                case 'query':
                    try {
                        $query = QueryFactory::createQueryFromString($call['method'], $call['args'], $call['id']);
                        $requestsResult[] = ResponseFactory::makeValidResponse($queryBus->handle($query), $call['id']);
                    } catch (CommandNotFoundException $e) {
                        $requestsResult[] = ResponseFactory::makeErrorResponse(
                            ResponseFactory::METHOD_NOT_FOUND,
                            $call['id']
                        );
                    } catch (InvalidArgumentsException $e) {
                        $requestsResult[] = ResponseFactory::makeErrorResponse(
                            ResponseFactory::INVALID_PARAMETERS,
                            $call['id']
                        );
                    }
                    break;
                case 'command':
                    try {
                        $command = CommandFactory::createCommandFromString($call['method'], $call['args']);
                        $commandBus->dispatch($command);
                    } catch (CommandNotFoundException $e) {
                        $requestsResult[] = ResponseFactory::makeErrorResponse(
                            ResponseFactory::METHOD_NOT_FOUND
                        );
                    } catch (InvalidArgumentsException $e) {
                        $requestsResult[] = ResponseFactory::makeErrorResponse(
                            ResponseFactory::INVALID_PARAMETERS
                        );
                    }
                    break;
                default :
                    $requestsResult[] = ['error' => ['code' => -32600, 'message' => 'Invalid RPC']];
            }
        }

        if (count($requestsResult) === 1) { // if a single request, must be single response
            $requestsResult = $requestsResult[0];
        }
        return new JsonResponse($requestsResult);
    }
}
