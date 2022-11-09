<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hierarchy;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CustomController extends Controller
{
    /**
     * Fetch LOB.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function custom1(){
        $lob = Hierarchy::groupBy('c1')->get()->pluck('c1');
        return response()->json([
            'status'   => '200',
            'message'  => "LOB fetch sucessfully.",
            'data'     => $lob
        ], 200);
    }

    /**
     * Fetch Vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function custom2(Request $request){
        $validator = Validator::make($request->all(), [
            'custom1' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => '422',
                'message'  => "Validation errors",
                'data'     => $validator->messages()
            ], 422);
        }

        $vendor = Hierarchy::where('c1', $request->custom1)->groupBy('c2')->get()->pluck('c2');
        return response()->json([
            'status'   => '200',
            'message'  => "Vendor fetch sucessfully.",
            'data'     => $vendor
        ], 200);
    }

    /**
     * Fetch Campaign.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function custom3(Request $request){
        $validator = Validator::make($request->all(), [
            'custom1' => 'required',
            'custom2' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => '422',
                'message'  => "Validation errors",
                'data'     => $validator->messages()
            ], 422);
        }

        $campaign = Hierarchy::where('c1', $request->custom1)->where('c2', $request->custom2)->groupBy('c3')->get()->pluck('c3');
        return response()->json([
            'status'   => '200',
            'message'  => "Campaign fetch sucessfully.",
            'data'     => $campaign
        ], 200);
    }

    /**
     * Fetch Location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function custom4(Request $request){
        $validator = Validator::make($request->all(), [
            'custom1' => 'required',
            'custom2' => 'required',
            'custom3' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => '422',
                'message'  => "Validation errors",
                'data'     => $validator->messages()
            ], 422);
        }

        $location = Hierarchy::where('c1', $request->custom1)->where('c2', $request->custom2)->where('c3', $request->custom3)->groupBy('c4')->get()->pluck('c4');
        return response()->json([
            'status'   => '200',
            'message'  => "Location fetch sucessfully.",
            'data'     => $location
        ], 200);
    }

    /**
     * Fetch Supervisor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSupervisor(Request $request){
        $supervisor = User::select('userId','name')->where('userStatus', 'Active')
        ->where(function($query) {
            $query->where('userRole', 'Supervisor');
            $query->orWhere('userRole', 'Manager');
        })->get();

        return response()->json([
            'status'   => '200',
            'message'  => "Supervisor fetch sucessfully.",
            'data'     => $supervisor
        ], 200);
    }
}
