<?php

    use Coco\sse\processor\StrandardProcessor;
    use Coco\sse\SSE;

    require './../vendor/autoload.php';

    $processor = new StrandardProcessor();

    SSE::init($processor);

    $updateEvent = SSE::getEventIns('update');

    $len = 50;

    for ($i = 0; $i < $len; $i++)
    {
        $updateEvent->send(json_encode([
            "id"   => $i,
            "data" => "data-" . $i,
        ]));

        usleep(1000 * 100);
    }

    SSE::getEventIns('close')->send(json_encode([
        "status" => "closed",
    ],256));