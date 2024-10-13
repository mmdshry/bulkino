<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMktSmsRequest;
use App\Http\Requests\SendOtpSmsRequest;
use App\Http\Requests\SendSmsRequest;
use App\Http\Resources\LogResource;
use App\Notifications\MktNotification;
use App\Notifications\OtpNotification;
use App\Notifications\SmsNotification;
use App\Services\KavenegarSmsService;
use App\Traits\ResponderTrait;
use Auth;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    use ResponderTrait;

    public function __construct(private readonly KavenegarSmsService $kavenegarSmsService)
    {
    }

    public function send(SendSmsRequest $request)
    {
        dd('shit!');
        Auth::user()->notify(new SmsNotification($request->validated()));

        return $this->responseSuccessful('Sms sent successfully!');
    }

    public function otp(SendOtpSmsRequest $request)
    {
        Auth::user()->notify(new OtpNotification($request->validated()));

        return $this->responseSuccessful('OTP sent successfully!');
    }

    public function mkt(SendMktSmsRequest $request)
    {
        Auth::user()->notify(new MktNotification($request->validated()));

        return $this->responseSuccessful('MKT sent successfully!');
    }

    public function status()
    {
        // Logic for checking SMS delivery status
        return $this->kavenegarSmsService->getAccountInfo();
    }

    public function logs()
    {
        return $this->response(LogResource::collection(Auth::user()->logs()->orderBy('created_at', 'DESC')->get()), 'Logs fetched successfully!');
    }



    public function account(Request $request)
    {
        return response()->json($request->user());
    }
}