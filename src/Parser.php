<?php

declare(strict_types=1);

namespace Imi\Grpc;

use Google\Protobuf\Internal\Message;

/**
 * gRPC 包处理类.
 *
 * 参考实现：https://www.jianshu.com/p/f3221df39e6f
 */
abstract class Parser
{
    public static function pack(string $data): string
    {
        return $data = pack('CN', 0, \strlen($data)) . $data;
    }

    public static function unpack(string $data): string
    {
        return $data = substr($data, 5);
    }

    public static function serializeMessage(Message $data): string
    {
        if (method_exists($data, 'encode'))
        {
            $data = $data->encode();
        }
        elseif (method_exists($data, 'serializeToString'))
        {
            $data = $data->serializeToString();
        }
        else
        {
            // @phpstan-ignore-next-line
            $data = $data->serialize();
        }

        return self::pack($data);
    }

    /**
     * @param mixed $deserialize
     */
    public static function deserializeMessage($deserialize, string $value): ?Message
    {
        if (empty($value))
        {
            return null;
        }
        else
        {
            $value = self::unpack($value);
        }
        if (\is_array($deserialize))
        {
            [$className, $deserializeFunc] = $deserialize;
            /** @var \Google\Protobuf\Internal\Message $obj */
            $obj = new $className();
            if ($deserializeFunc && method_exists($obj, $deserializeFunc))
            {
                $obj->{$deserializeFunc}($value);
            }
            else
            {
                $obj->mergeFromString($value);
            }

            return $obj;
        }

        return $deserialize($value);
    }

    /**
     * @param mixed $response
     * @param mixed $deserialize
     */
    public static function parseToResultArray($response, $deserialize): array
    {
        if (!$response)
        {
            // @phpstan-ignore-next-line
            return ['No response', GRPC_ERROR_NO_RESPONSE, $response];
        }
        elseif (200 !== $response->statusCode)
        {
            return ['Http status Error', $response->errCode ?: $response->statusCode, $response];
        }
        else
        {
            $grpc_status = (int) ($response->headers['grpc-status'] ?? 0);
            if (0 !== $grpc_status)
            {
                return [$response->headers['grpc-message'] ?? 'Unknown error', $grpc_status, $response];
            }
            $data = $response->data;
            $reply = self::deserializeMessage($deserialize, $data);
            $status = (int) ($response->headers['grpc-status'] ?? 0 ?: 0);

            return [$reply, $status, $response];
        }
    }
}
