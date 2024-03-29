<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: grpc.proto

namespace Grpc;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>grpc.LoginRequest</code>.
 */
class LoginRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * 手机号.
     *
     * Generated from protobuf field <code>string phone = 1;</code>
     */
    private $phone = '';
    /**
     * 密码
     *
     * Generated from protobuf field <code>string password = 2;</code>
     */
    private $password = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     *     @var string
     *           手机号
     *     @var string
     *           密码
     * }
     */
    public function __construct($data = null)
    {
        \GPBMetadata\Grpc::initOnce();
        parent::__construct($data);
    }

    /**
     * 手机号.
     *
     * Generated from protobuf field <code>string phone = 1;</code>
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * 手机号.
     *
     * Generated from protobuf field <code>string phone = 1;</code>
     *
     * @param string $var
     *
     * @return $this
     */
    public function setPhone($var)
    {
        GPBUtil::checkString($var, true);
        $this->phone = $var;

        return $this;
    }

    /**
     * 密码
     *
     * Generated from protobuf field <code>string password = 2;</code>
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * 密码
     *
     * Generated from protobuf field <code>string password = 2;</code>
     *
     * @param string $var
     *
     * @return $this
     */
    public function setPassword($var)
    {
        GPBUtil::checkString($var, true);
        $this->password = $var;

        return $this;
    }
}
