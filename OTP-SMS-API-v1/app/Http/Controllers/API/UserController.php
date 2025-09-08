<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Ipe\Sdk\Facades\SmsIr;
use GuzzleHttp\Client;
use SebastianBergmann\Type\FalseType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserContorller extends Controller
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
        2)recive and validate the user vote also if user voted before we delete his/her vote and save it again with new data!
        3)if all were correct 2 tokens will be generated which are an otp token and a sanctum token for authentication
        4)a post request will be send to the sms.ir api inorder to send and sms to the user containing the otp token
        */
        #validating the mobile number 11 to 12 digits that starts with 09 numbers like 09125465422
        $Verified_players = ['messi', 'ronaldo', 'haaland', 'lewandowski'];
        $dataVal = $request->validate([
            'playerName' => 'required|string',
            'mobileNumber' => 'required|regex:/^09\d{9,10}$/'
        ]);
        //میگرده ببینه که رای طرف جزو موارد پذیرفتی هست؟
        $flag = in_array(
            strtolower($dataVal['playerName']),
            $Verified_players
            );
            //اگر نبود؟
        if(!$flag) {
            return response()->json([
                'data' => [],
                'playerName' => 'the Player Name is not in the voting system! you must choose between 1)messi 2)ronaldo 3)haaland 4)lewandowski',
                'success' => 'false'
            ],400);
        }
        #اگر کاربر قبلا رای داده بود ما اطلاعاتش را ریست میکنیم . از اول برایش ثبت میکنیم

        $user__isCompleted = User::where('mobileNum', $dataVal['mobileNumber'])->where('completed', '=', 1)
        ->orWhere('completed', '=', 0)->first();
        if($user__isCompleted){
            $user__isCompleted->votes()->delete();
            $user__isCompleted->tokens()->delete();
            $user__isCompleted->delete();
        }

        $user = User::create(['mobileNum' => $dataVal['mobileNumber']]);
        $user->votes()->create([
            'footballPlayerName' => $dataVal['playerName']
        ]);

        //شماره گوشی کاربر
        $token_name = $dataVal['mobileNumber'];
        #ساخت توکن سنکتوم جهت اهراز هویت
        $auth_token = $user->createToken($token_name)->plainTextToken;

        #ساخت و ذخیره توکن otp
        #برای فرستادن با  پیامک
        $otp_token = substr(str_shuffle(Str::random(8)), 0, 6);

        #hashing the otp token
        $hashed_otp = Hash::make($otp_token, ['rounds' => 12]);
        #ذخیره توکن او تی پی در پایگاه داده
        $user->OTP_Token = $hashed_otp;
        $user->save();

        // $token_found = $user->tokens()->where('name', $token_name)->first(); //will fetch hashed version from db not plain text one
        // $sent_token = $token_found; //این روش لازم نشد اما خیلی جالبه

        //موارد پایین مربوط به ای پی آی sms.ir
        //هست که مطابق مستندات خود سایت sms.ir نوشته شدع
        //از خط 83 تا 92
        $mobile = $dataVal['mobileNumber'];//$mobileNum; // شماره موبایل گیرنده
        $templateId = 123456; // شناسه الگو
        //بدنه درخواست به ای پی آی sms.ir
        $data = [
            'mobile' => $mobile,
            'templateId' => $templateId,
            'parameters' => [
                [
                    'name' => 'code', //نام کلید؟
                    'value' => $otp_token
                ]
            ]
        ];
        try {
            //در اینجا ما به ای پی آیی که قرار است پیامک بفرستد درخواست بزنیم
            $client = new Client(['verify' => false]);
            $response = $client->post(
                'https://api.sms.ir/v1/send/verify',
                [
                    "json" =>  $data,
                    "headers" => [
                        'Accept' => 'text/plain',
                        'Content-Type'  => 'application/json',
                        'X-API-KEY' => 'gsyDIlM3faJssXXUvakqAuBVf9Zw77KTC6cKQph4rcdcs4ge',
                    ]
                ]
            );
            //گرفتن بده پاسخ ای پی آی sms.ir
            $responseBody = json_decode($response->getBody(), true);
            //اینم از پاسح جسوون شخصی سسازی شده خودم که قابل مشاعدع است
            return response()->json([
                'data' => $responseBody,
                'message' => 'OTP sent successfully',
                'success' => true,
                'OTP-Token' => $otp_token,
                'auth-Token' => $auth_token
            ], 200);
            //در غیر این صورت چطور؟ پایین پاسخ هست
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'message' => 'Failed to send OTP ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
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
