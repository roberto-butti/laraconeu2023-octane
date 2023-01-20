<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\App;
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

Route::get('/', function () {
    $server = App::make(Swoole\Http\Server::class);
    $workerId = $server->getWorkerId();
    Log::debug('REQUEST '.$workerId);

    return view('welcome');
});
