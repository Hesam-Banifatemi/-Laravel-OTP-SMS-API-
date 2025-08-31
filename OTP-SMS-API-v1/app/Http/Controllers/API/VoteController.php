<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); //اگر قراره این آخرین کاربر احراز هویت شده رو بیارود و بعد بر اساس توکن سنکتوم اش رای اش را به ما نشان بدهد چون از
        //routeModel binding خبری نیست و ما چه راهیی خواهیم داشت؟
        //راه حل به کاربری Auth هست
        //votes of the authenticated user
        $vote = $user->votes->first();
        return response()->json([
            'data' => [
                "Your phone number: {$user->mobileNum} & Your Vote: {$vote->footballPlayerName}"
            ],
            'message' => 'Your Vote is ' .  $vote->footballPlayerName . ' Thanks for voting',
            'success' => true
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
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
