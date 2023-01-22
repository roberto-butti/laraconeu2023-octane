<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/*
|--------------------------------------------------------------------------
| Managing static properties
|--------------------------------------------------------------------------
|
| Showing the side effects with static properties
|
*/
Route::get('/add-item', [ExampleController::class, 'addItem'])->name('static-props');

/*
|--------------------------------------------------------------------------
| Optimizing routes
|--------------------------------------------------------------------------
|
| Using Octane::route()
|
 */
use Laravel\Octane\Facades\Octane;
use Symfony\Component\HttpFoundation\Response;

Octane::route('GET', '/route-octane', function () {
    return new Response('Hi LaraconEU, we have "Octane Route".');
});
Route::get('/route-get',
function () {
    return new Response('Hi LaraconEU, we have "Laravel Route".
        Try <a href="/route-octane">Octane Route</a>');
}
)->name('route-get');

/*
|--------------------------------------------------------------------------
| Parallel functions
|--------------------------------------------------------------------------
|
| Using Octane::concurrently()
|
*/
function functions()
{
    $randomInt = function () {
        sleep(2);

        return random_int(1, 10);
    };

    return [$randomInt, $randomInt];
}
Route::get('/serial-task', function () {
    [$result1, $result2] = functions();

    return new Response('Hi LaraconEU, (one task at a time), '.$result1().' - '.$result2());
})->name('serial-task');

Route::get('/parallel-task', function () {
    [$result1, $result2] = Octane::concurrently(
        functions()
    );

    return new Response('Hi LaraconEU, (task in parallel), '.$result1.' - '.$result2);
})->name('parallel-task');

/*
|--------------------------------------------------------------------------
| Cache
|--------------------------------------------------------------------------
|
| Using Cache::store('octane') for getting instance
| put() to set the value in cache
| get() to get the value from cache
|
*/
Route::get('/set-random-number', function () {
    $number = random_int(1, 6);
    Cache::store('octane')->put('last-random-number', $number);

    return $number;
})->name('set-cache');

Route::get('/get-random-number', function () {
    $number = Cache::store('octane')->get('last-random-number', 0);

    return $number;
})->name('get-cache');

/*
|--------------------------------------------------------------------------
| Cache Only strategy
|--------------------------------------------------------------------------
|
| The cached number is filled in AppServiceProvider using tick() method.
| Here, we retrieve the value from cache.
| Strong assumption: the value is stored in the cache.
|
*/
Route::get('/get-random-number-cache-only', function () {
    $number = Cache::store('octane')->get('number-cacheonly', 0);

    return $number;
})->name('get-cache-only');

/*
|--------------------------------------------------------------------------
| Swoole Table
|--------------------------------------------------------------------------
|
| - Create values in table
| - Get a value in table
|
*/
Route::get('/create-table', function () {
    $faker = Faker\Factory::create();
    $table = Octane::table('example');
    for ($i = 0; $i < 500; $i++) {
        $table->set($i,
            [
                'name' => $faker->firstName(),
                'votes' => random_int(1, 1000),
            ]);
    }

    return "Created $i rows";
})->name('create-table');

Route::get('/get-table', function () {
    $table = Octane::table('example');

    return $table->get(random_int(1, 500));
})->name('get-table');

/*
|--------------------------------------------------------------------------
| Using Swoole methods
|--------------------------------------------------------------------------
|
| Using Swoole with Laravel Octane,
| you can access to Swoole methods, for example stats()
|
*/
Route::get('/metrics', function () {
    $server = App::make(Swoole\Http\Server::class);

    return $server->stats(\OPENSWOOLE_STATS_OPENMETRICS);
})->name('metrics');

Route::get('/', function () {
    $workerId = '';
    try {
        $server = App::make(Swoole\Http\Server::class);
        $workerId = $server->getWorkerId();
    } catch (Exception $e) {
        $workerId = '-';
    }
    Log::debug('REQUEST '.$workerId);

    return view('welcome');
});
