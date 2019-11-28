<?php
namespace ImiApp\Listener;

use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Process\ProcessManager;
use Imi\Bean\Annotation\Listener;

/**
 * @Listener(eventName="IMI.SERVERS.CREATE.AFTER")
 */
class OnServerCreateAfter implements IEventListener
{
    /**
     * 事件处理方法
     * @param EventParam $e
     * @return void
     */
    public function handle(EventParam $e)
    {
        // 挂载进程到 Manager 进程下
        // ProcessManager::runWithManager('Test');
    }
}