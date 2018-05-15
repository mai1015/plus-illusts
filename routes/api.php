<?php

use Illuminate\Support\Facades\Route;
use Mai1015\PlusIllusts\API\Controllers as API;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::model('pid', \Mai1015\PlusIllusts\Models\PixivUser::class, function()
{
    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
});
Route::model('wid', \Mai1015\PlusIllusts\Models\Illust::class);

Route::group(['prefix' => 'api/illusts'], function (RouteRegisterContract $api) {
    // Test route.
    // @ANY /api/plus-illusts
    $api->any('/', API\HomeController::class.'@index');
});

Route::group(['prefix' => 'api/illusts/user'], function (RouteRegisterContract $api) {
    // @get get user
    $api->get('/{pid}', API\PixivUserController::class.'@show');
    // @post create one pixiv user
    $api->post('/', API\PixivUserController::class.'@store');
    // @delete remove one pixiv user
    $api->delete('/{pid}', API\PixivUserController::class . '@destroy');
    // @patch update user
    $api->patch('/{pid}', API\PixivUserController::class . '@update');
});

Route::group([
    'prefix' => 'api/illust',
    'middleware' => Mai1015\PlusIllusts\API\Middleware\CrawlerKeyVerify::class,
], function (RouteRegisterContract $api) {
    // @get /api/illust
    $api->get('/', API\IllustController::class.'@index');
    // @post create one work
    $api->post('/', API\IllustController::class.'@store')->middleware('sensitive:title,caption,thumb');
    // @delete remove work
    $api->delete('/{wid}', API\IllustController::class.'@destroy');
    // @patch update work
    $api->patch('/{wid}', API\IllustController::class . '@update');
    // @get show files
    $api->get('/{wid}', API\IllustController::class.'@show');
    // @post upload files
    $api->post('/{wid}/file', API\IllustController::class.'@store_file');
    // @delete file
    $api->delete('/{wid}/file', API\IllustController::class.'@delete_file');
    // @post add tag
    $api->post('/{wid}/tag', API\TagController::class.'@store');
    // @delete remove tag
    $api->delete('/{wid}/tag', API\TagController::class . '@destroy');
    // @post create tags id
    $api->post('/tags', API\TagController::class . '@create');
    // @post create tags
    //$api->post('')
});

Route::group([
    'prefix' => 'api/illust-crawler',
    'middleware' => Mai1015\PlusIllusts\API\Middleware\CrawlerKeyVerify::class,
], function (RouteRegisterContract $api) {
    // @get one pixiv user
    $api->get('/', API\CrawlerController::class.'@show');
    // @post create one pixiv user
    $api->post('/', API\HomeController::class.'@store');
});

