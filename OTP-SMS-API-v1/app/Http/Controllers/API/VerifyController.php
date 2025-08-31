<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ipe\Sdk\Facades\SmsIr;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VerifyController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*The targets of this method
        1)reciece and validate the user mobile number
        2)recive and validate the user OTP Token
        3)if all were correct for user in the DB the completed column became true;
        */
        #شمارع همراه کاربر با توکن پیامک شده را میگیرم همان او تی پی
        $data = $request->validate([
                'mobileNumber' => 'required',
                'token' => 'required'
            ]);
        #کاربر را بر اساس آن توکن دریافتی از پایگاه داده میگیرم
        $user = User::where('mobileNum', $request->mobileNumber)->first();
        #اگر کاربر نبود پیام جسون زیر را که پاسخ میباشد بفرست
        if(!$user) {
            return response()->json([
                'data' => [],
                'message' => 'the provided crediential are incorrect',
                'success' => false,
            ], 400);
        }
        //$hashed_token = $user->tokens()->where('name', $request->mobileNumber)->first();
        #توکن کاربر از پایگاه داده گرفته میشود برای مقایسع با توکن دریافتی از کاربر جهت احراز هویت
        $OTP_Token = $user->OTP_Token; //which is hashed
        #اگر توکن ها با هم دگرسان بودند  این پاسخ است
        if(!Hash::check($request->token, $OTP_Token)) {
            return response()->json([
                'data' => [],
                'message' => 'the provided crediential are incorrect',
                'success' => false,
            ], 400);
        }
        #اگر همه مراحل درست بود کافیست که ما در پایگاه داده بنویسیم که فرایند کامل شد با قرار داد مقدار 1 یا همان ترو در ستون  completed
        $user->completed = 1;
        $user->save();
        return response()->json([
            'data' => [],
            'message' => 'Your vote has been saved and please visite /api/vote...',
            'success' => true
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
