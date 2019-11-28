<?php
namespace Imi\Server\Grpc\Error;

use Imi\RequestContext;
use Imi\Server\Http\Error\IErrorHandler;

class GrpcErrorHandler implements IErrorHandler
{
    protected $handler = DefaultGrpcErrorHandler::class;

    public function handle(\Throwable $throwable): bool
    {
        return RequestContext::getServerBean($this->handler)->handle($throwable);
    }

}