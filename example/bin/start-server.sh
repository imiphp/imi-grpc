#!/bin/bash

__DIR__=$(cd `dirname $0`; pwd)

${__DIR__}/stop-server.sh

nohup $__DIR__/imi server/start > /dev/null 2>&1 & echo $! > "$__DIR__/server.pid"
