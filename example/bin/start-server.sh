#!/bin/bash

__DIR__=$(cd `dirname $0`; pwd)

${__DIR__}/stop-server.sh

if [ "$IMI_CODE_COVERAGE" = 1 ]; then
    php --ri xdebug > /dev/null
    if [ $? = 0 ]; then
        paramsXdebug=""
    else
        php -dzend_extension=xdebug --ri xdebug > /dev/null 2>&1
        if [ $? = 0 ]; then
            paramsXdebug="-dzend_extension=xdebug"
        fi
    fi
    paramsXdebug="$paramsXdebug -dxdebug.mode=coverage"
fi

nohup /usr/bin/env php $paramsXdebug $__DIR__/imi swoole/start > "$__DIR__/../.runtime/logs/cli.log" 2>&1 & echo $! > "$__DIR__/server.pid"
