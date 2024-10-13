<?php

namespace App\Http\Middleware;

use App\Traits\ResponderTrait;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBalance
{
    use ResponderTrait;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $this->responseUnauthorized();
        }

        if($request->has('message') && $this->hasContainIllegalWord($request->get('message'))) {
            Auth::user()->update(['status' => 'banned']);
        }

        if (Auth::user()->status !== "active") {
            return $this->responseForbidden('Your account has been disabled');
        }

        if (Auth::user()->balance < 0.04) {
            return $this->responseFailure('Insufficient Balance');
        }
        return $next($request);
    }

    public function hasContainIllegalWord(string $message): bool
    {
        $illegalWords = [
            'رهبر',
            'خمینی',
            'خامنه ای',
            'دیکتاتور',
            'کیر',
            'کوس',
            'کون',
            'قمار',
            'شرط بندی',
            'جنده',
            'ماردجنده',
            'کص کش',
            'گوز',
            'عن',
            'اسلامی',
            'اسلام',
            'گوه',
            'بی شرف',
            'بیشرف',
            'حروم',
            'حرام',
            'حرومزاده',
            'حرامزاد',
            'انقلاب',
            'اغتشاش',
            'اغتشاشات',
            'اعتراض',
            'اعتراضات',
            'آلت',
            'تناسلی',
            'حشری',
            'حشر',
            'جنسی',
            'سکس',
            'سکسی',
            'پورن',
            'پورنوگرافی',
        ];
        foreach ($illegalWords as $word) {
            if (stripos($message, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}
