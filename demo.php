<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Demo\Scheduler;
use Fiber;

$scheduler = new Scheduler();

$apiData = null;
$databaseData = null;

$scheduler->add(static function () use (&$apiData): void {
    echo "[API] Sending request...\n";
    Fiber::suspend();

    echo "[API] Waiting for the response...\n";
    Fiber::suspend();

    $apiData = ['user_id' => 42, 'plan' => 'pro'];
    echo "[API] Response received\n";
});

$scheduler->add(static function () use (&$databaseData): void {
    echo "[DB] Starting query...\n";
    Fiber::suspend();

    echo "[DB] Waiting for the database...\n";
    Fiber::suspend();

    $databaseData = ['user_id' => 42, 'orders' => 3];
    echo "[DB] Query finished\n";
});

echo "Starting scheduler...\n\n";

$scheduler->run();

echo "\nProcessing combined data...\n";
printf(
    "User %d has a %s plan and %d orders.\n",
    $apiData['user_id'],
    $apiData['plan'],
    $databaseData['orders']
);

echo "\nAll tasks finished.\n";
