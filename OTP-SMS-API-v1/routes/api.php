<?php

use App\Http\Controllers\API\UserContorller;
use App\Http\Controllers\API\VerifyController;
use App\Http\Controllers\API\VoteController;
use Illuminate\Support\Facades\Route;

/*        __     _____    ____
        /    \   |____|    ||
       / _____\  |         ||
      /        \ |         ||
     |          ||        ____
*/
/*in this API The goal is to, after receiving the necessary credentials from the user like
mobile number, and vote, a sms will be sent containing the otp token using a thrid party api
which is sms.ir api,and whose job is to sending sms to the user.
so we have three end points
1) /api/generate //whose job is the receive the necessary credentials and validate them
and finally sends and sms with otp token
2) /api/verify //whose job is to verify the user and datas from user based on mobile number
OTP token and after that the user votes will set completed in DB
3) /api/vote //whose job is to show the user's vote only to the user base on the sanctum token
the user have.
*/
Route::apiResource('generate', UserContorller::class)
->only(['store' ]); //همان /api/generate

Route::apiResource('verify', VerifyController::class)
->only(['store'])->middleware('auth:sanctum'); //همان /api/verify

Route::apiResource('vote', VoteController::class)
->only(['index'])->middleware('auth:sanctum'); //همان /api/vote
//توضیحات هررر کدام از از روت ها در بالااا مطالعه بفرمایید
