<?php

use App\Models\User;
use App\Notifications\MktNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

Route::get('/tokens/create', function (Request $request) {
    $token = User::first()->createToken('api');

    dd(['token' => $token->plainTextToken]);
});

Route::get('/', function () {
    dd('fuck!');
    Notification::route('sms', '09190755375')->notify(new MktNotification('سلام'));
    dd('ok!');

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL            => 'https://api.sms.ir/v1/send?username=09190755375&password=CkNhey1hcTNcprz9H1t0aRfZgByc7QAdGabV3yjNgsmNwia83iGjeEZFsGm7fLVm&mobile=MOBILE&line=09999932680&text=سلام',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => array(
            'Accept: text/plain'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    dd($response);

    $url = "https://panel.asanak.com/webservice/v1rest/sendsms";

    $payload = [
        'username'    => '09190755375',
        'password'    => 'Msh09190755375!!',
        'Source'      => '9821021000',
        'Message'     => 'YOUR_MESSAGE',
        'destination' => '989XXXXXXXXX'
    ];

    $response = Http::timeout(5)->asForm()->post($url, $payload);

    echo $response->body();

    $response = Http::asForm()->post('https://panel.asanak.com/webservice/v1rest/msgstatus', [
        'username' => '09190755375',
        'password' => 'Msh09190755375!!',
        'msgid'    => 'YOUR_MESSAGE_ID'
    ]);

    echo $response->body();
    $response = Http::withHeaders([
        'username' => '09190755375',
        'password' => 'Msh09190755375!!',
        'Cookie'   => 'asanak=gut3ps5tldh3nnvbvm2hnr6i75'
    ])->post('https://panel.asanak.com/webservice/v1rest/getcredit');

    dd($response->body());
});
