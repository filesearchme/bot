<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Http\Response;
use Illuminate\Http\Request;

$app->get('/', function (Request $request) use ($app) {

    #$return = App\Hosts\Turbobit::parse('http://turbobit.net/2uaf6bkv0mtr.html','158.69.252.131');
    #$return = App\Hosts\Mediafire::parse('http://www.mediafire.com/file/phx4u8qzx7mz07f/ILOVEMAKONNEN+%E2%80%93+Super+Chef+3+%28Freestyle%29+%28musicdabster.com%29.mp3','158.69.252.131');

    #dd( $return );

    return Response()->json(
        [
            'bot' => 'FileSearch.me',
            'version' => env('APP_BOT_VERSION'),
            'identifier' => md5($request->server('SERVER_ADDR')),
            'user_agent' => 'FileSearch.me Bot v'.env('APP_BOT_VERSION').' '.md5($request->server('SERVER_ADDR'))
        ],
        200,
        [],
        JSON_PRETTY_PRINT
    );
});
