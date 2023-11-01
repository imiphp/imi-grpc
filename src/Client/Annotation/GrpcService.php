<?php

declare(strict_types=1);

namespace Imi\Grpc\Client\Annotation;

use Imi\Bean\Annotation\Inherit;
use Imi\Bean\BeanFactory;
use Imi\Rpc\Annotation\RpcService;

/**
 * gRPC 服务对象注入.
 *
 * @Annotation
 *
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @property string|null $interface 服务接口
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
#[Inherit]
class GrpcService extends RpcService
{
    public function __construct(?array $__data = null, string $name = '', array $args = [], ?string $poolName = null, ?string $serviceName = null, ?string $interface = null)
    {
        parent::__construct(...\func_get_args());
    }

    /**
     * 获取注入值的真实值
     *
     * @return mixed
     */
    public function getRealValue()
    {
        return BeanFactory::newInstance(\Imi\Grpc\Client\ServiceAgent::class, $this->poolName, $this->serviceName, $this->interface);
    }
}
