<?php

/* Autoload is not required, (Swoole comes from a PHP module) */
$server = new Swoole\HTTP\Server('127.0.0.1', 9501);
$server->set([
    'worker_num' => 5, // 5 workers up and runnig
/*
Algorithm used to dispatch connections to workers
https://openswoole.com/docs/modules/swoole-server/configuration#dispatch_mode
*/
    'dispatch_mode' => 1, // 1 = Round Robin
]);











// Triggered when new worker processes starts
$server->on('WorkerStart', function ($server, $workerId) {
    echo 'Worker starts:'.$server->getWorkerId().
        ' pid <'.$server->getWorkerPid().'>'.PHP_EOL;
});

$server->on('Start', function (Swoole\Http\Server $server) {
    echo 'Swoole http server is started at '.
        sprintf('http://%s:%s', $server->host, $server->port).
        PHP_EOL;
});

$server->on('request', function (
    Swoole\Http\Request $request,
    Swoole\Http\Response $response
) use ($server) {
    $response->header('Content-Type', 'text/plain');
    $response->end('Hello World served by '.$server->getWorkerId());
});









/*
| Starts the Swoole Server,
| will create worker_num + 2 processes by default
| (Master, Manager, Workers)
| try to execute command: wrk http://127.0.0.1:9501/ -d10s
 */
$server->start();
echo ' AFTER'.PHP_EOL;












