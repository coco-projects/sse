<?php

    declare(strict_types = 1);

    namespace Coco\sse\processor;

    use Psr\Http\Message\ResponseInterface;

class Psr7Processor extends ProcessorAbstract
{
    public ?ResponseInterface $response = null;

    public function __construct(?ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function initHeader(array $headers): static
    {
        foreach ($headers as $k => $v) {
            $this->response = $this->response->withHeader($k, $v);
        }

        return $this;
    }

    public function send(string $msg): static
    {
        $this->response->getBody()->write($msg);

        return $this;
    }
}
