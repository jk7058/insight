<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackSettingRequest;
use App\Models\FeedbackSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Store Feedback Settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function feedbackSetting(FeedbackSettingRequest $request){
        $setting = FeedbackSetting::first();
        $setting->update($request->all());

        return response()->json([
            'status'   => '200',
            'message'  => "Feedback Setting updated sucessfully.",
            'data'     => $setting
        ], 200);
    }

    /**
     * Get Feedback Settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFeedbackSetting(){
        $setting = FeedbackSetting::first();

        return response()->json([
            'status'   => '200',
            'message'  => "Feedback Setting fetch sucessfully.",
            'data'     => $setting
        ], 200);
    }
}
