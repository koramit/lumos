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

$router->get('/', function () use ($router) {
    return \App\CurrentStatus::all()[0];
    return $router->app->version();
});

$router->post('/get-status', function (Illuminate\Http\Request $request) {
    if ( $request->token != env('KT_token') ) {
        return ['reply_code' => 9, 'reply_text' => 'not allow'];
    }

    $status = App\CurrentStatus::getStatus($request->hn);

    if (!$status) {
        return ['reply_code' => 1, 'reply_text' => 'ไม่พบข้อมูล'];
    }

    $patient = (new App\APIs\PatientDataProvider)->getPatient($request->hn);

    $reply  = "ผป. : " . $request->hn . "\n";
    $reply .= "ชื่อ : " . (isset($patient['first_name']) ? mb_substr($patient['first_name'], 0, 2) . '***' : '') . "\n";
    $reply .= "สกุล : " . (isset($patient['last_name']) ? mb_substr($patient['last_name'], 0, 2) . '***' : '') . "\n";
    $reply .= "ตรวจเมื่อ : " . ($status->DateFU ? $status->DateFU->format('d-m-Y') : '') . "\n";
    $reply .= "สถานะ : " . $status->getStatusThai() . "\n";

    return ['reply_code' => 0, 'reply_text' => $reply];
});
