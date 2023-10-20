<?php

declare(strict_types=1);

namespace Imi\Grpc\Server\Error;

use Imi\Grpc\Enum\GrpcStatus;
use Imi\RequestContext;
use Imi\Server\Http\Error\IErrorHandler;
use Imi\Util\Http\Consts\MediaType;
use Imi\Util\Http\Consts\RequestHeader;

class DefaultGrpcErrorHandler implements IErrorHandler
{
    public function handle(\Throwable $throwable): bool
    {
        /** @var \Imi\Server\Http\Message\Response $response */
        $response = RequestContext::get('response');
        $response->setHeader(RequestHeader::CONTENT_TYPE, MediaType::GRPC_PROTO);
        if (!$response->hasHeader(RequestHeader::TRAILER))
        {
            $response->setHeader(RequestHeader::TRAILER, 'grpc-status, grpc-message');
        }
        if (!$response->hasTrailer('grpc-status'))
        {
            $response->setTrailer('grpc-status', (string) $this->getGrpcStatus($throwable));
        }
        if (!$response->hasTrailer('grpc-message'))
        {
            $response->setTrailer('grpc-message', $this->getGrpcMessage($throwable));
        }
        $response->send();

        return false;
    }

    private function getGrpcStatus(\Throwable $throwable): int
    {
        $class = $throwable::class;
        switch ($class)
        {
            case \BadFunctionCallException::class:
                return GrpcStatus::UNKNOWN;
            case \BadMethodCallException::class:
                return GrpcStatus::UNKNOWN;
            case \DomainException::class:
                return GrpcStatus::OUT_OF_RANGE;
            case \InvalidArgumentException::class:
                return GrpcStatus::INVALID_ARGUMENT;
            case \LengthException::class:
                return GrpcStatus::INVALID_ARGUMENT;
            case \LogicException::class:
                return GrpcStatus::UNKNOWN;
            case \OutOfBoundsException::class:
                return GrpcStatus::OUT_OF_RANGE;
            case \OutOfRangeException::class:
                return GrpcStatus::OUT_OF_RANGE;
            case \OverflowException::class:
                return GrpcStatus::OUT_OF_RANGE;
            case \RangeException::class:
                return GrpcStatus::OUT_OF_RANGE;
            case \RuntimeException::class:
                return GrpcStatus::UNKNOWN;
            case \UnderflowException::class:
                return GrpcStatus::OUT_OF_RANGE;
            case \UnexpectedValueException::class:
                return GrpcStatus::OUT_OF_RANGE;
            default:
                return GrpcStatus::UNKNOWN;
        }
    }

    private function getGrpcMessage(\Throwable $throwable): string
    {
        return $throwable->getMessage();
    }
}
