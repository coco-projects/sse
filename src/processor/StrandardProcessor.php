<?php

    declare(strict_types = 1);

    namespace Coco\sse\processor;

class StrandardProcessor extends ProcessorAbstract
{
    public function initHeader(array $headers): static
    {
        foreach ($headers as $k => $v) {
            header("$k: $v");
        }

        return $this;
    }

    public function send(string $msg): static
    {
        // 如果输出缓冲区已经启用，先清空缓冲区
        if (ob_get_level() > 0) {
            ob_end_clean(); // 结束并清空缓冲区
        }

        // 输出消息
        echo $msg;

        // 检查缓冲区是否启用，如果启用则刷新
        if (ob_get_level() > 0) {
            ob_flush(); // 刷新缓冲区
        }

        // 刷新输出流
        flush();

        return $this;
    }
}
