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
        echo $msg;

        @flush();

        if (ob_get_level() > 0) {
            ob_flush();
        }

        return $this;
    }
}
