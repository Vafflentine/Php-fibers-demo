<?php

declare(strict_types=1);

namespace Demo;

use Fiber;
use SplQueue;

final class Scheduler
{
    /** @var SplQueue<Fiber> */
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function add(callable $task): void
    {
        $this->queue->enqueue(
            new Fiber(static function () use ($task): void {
                $task();
            })
        );
    }

    public function run(): void
    {
        while (!$this->queue->isEmpty()) {
            /** @var Fiber $fiber */
            $fiber = $this->queue->dequeue();

            if (!$fiber->isStarted()) {
                $fiber->start();
            } elseif ($fiber->isSuspended()) {
                $fiber->resume();
            }

            if (!$fiber->isTerminated()) {
                $this->queue->enqueue($fiber);
            }
        }
    }
}
