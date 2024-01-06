<?php

    declare(strict_types = 1);

    namespace Coco\sse;

    use Coco\sse\processor\ProcessorAbstract;

class SSE
{
    /**
     * @var $instances SSE[]
     */
    private static array              $instances = [];
    private static bool               $inited    = false;
    private static ?ProcessorAbstract $processor = null;
    private int                       $retry     = 5;
    private int                       $id        = 0;

    private static array $initHeader = [
        'Content-type'      => 'text/event-stream',
        'Cache-Control'     => 'no-cache',
        'X-Accel-Buffering' => 'no',
        'Content-Encoding'  => 'none',
    ];

    public function __construct(private string $eventName)
    {
    }

    public static function getEventIns($eventName): static
    {
        if (!isset(static::$instances[$eventName])) {
            static::$instances[$eventName] = new static($eventName);
        }

        return static::$instances[$eventName];
    }


    public static function init(ProcessorAbstract $processor): void
    {
        if (!static::$inited) {
            static::$processor = $processor;
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', 1);
            }

            ignore_user_abort(true);
            @set_time_limit(0);
            @ini_set('zlib.output_compression', '0');
            @ini_set('implicit_flush', '1');

            ob_implicit_flush(true);

            static::$processor->initHeader(static::$initHeader);
            static::$inited = true;
        }
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function setRetry(int $retry): static
    {
        $this->retry = $retry;

        return $this;
    }

    private function getNextId(): int
    {
        $this->id++;

        return $this->id;
    }

    public function send($data): void
    {
        $msg = implode(PHP_EOL, [
            "id:" . $this->getNextId(),
            "event:" . $this->eventName,
            "retry:" . $this->retry,
            "data:$data",
            PHP_EOL,
        ]);

        static::$processor->send($msg);
    }
}
