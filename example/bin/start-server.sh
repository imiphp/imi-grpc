#!/bin/bash

__DIR__=$(cd `dirname $0`; pwd)

${__DIR__}/stop-server.sh

php --ri xdebug > /dev/null
if [ $? -eq 0 ]; then
    paramsXdebug=""
else
    php -dzend_extension=xdebug --ri xdebug > /dev/null
    if [ $? -eq 0 ]; then
        paramsXdebug="-dzend_extension=xdebug"
    fi
fi

nohup /usr/bin/env php $paramsXdebug -dxdebug.mode=coverage $__DIR__/imi swoole/start > "$__DIR__/../.runtime/logs/cli.log" 2>&1 & echo $! > "$__DIR__/server.pid"
