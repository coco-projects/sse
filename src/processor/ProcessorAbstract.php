<?php

    namespace Coco\sse\processor;

abstract class ProcessorAbstract
{
    abstract public function initHeader(array $headers): static;

    abstract public function send(string $msg): static;
}
