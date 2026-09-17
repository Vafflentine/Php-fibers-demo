# PHP Fibers Demo

A tiny CLI project that demonstrates how PHP Fibers work.

## Requirements

- PHP 8.1+
- Composer

Check your PHP version:

```bash
php -v
```

## Install

```bash
composer install
```

## Run

```bash
php demo.php
```

Expected output:

```text
Starting scheduler...

[API] Sending request...
[DB] Starting query...
[API] Waiting for the response...
[DB] Waiting for the database...
[API] Response received
[DB] Query finished

Processing combined data...
User 42 has a pro plan and 3 orders.

All tasks finished.
```

## What is happening?

The API request and database query run inside separate `Fiber` instances.

They are independent, so the scheduler can switch to the other task while one
is waiting for an external result.

When a task calls:

```php
Fiber::suspend();
```

the Fiber pauses and returns control to `Scheduler`.

The scheduler puts unfinished Fibers back into the queue and later calls:

```php
$fiber->resume();
```

This creates cooperative multitasking.

Fibers do **not** create OS threads and do **not** execute PHP code in parallel.
They provide a convenient way to pause and resume execution.

## Main API used

- `new Fiber(callable $callback)`
- `$fiber->start()`
- `Fiber::suspend()`
- `$fiber->resume()`
- `$fiber->isStarted()`
- `$fiber->isSuspended()`
- `$fiber->isTerminated()`
