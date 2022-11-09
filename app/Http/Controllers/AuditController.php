<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Common;
use App\Models\FormData;

class AuditController extends Controller
{
    /**
     * Available audit list.
     *
     * @return \Illuminate\Http\Response
     */
    public function availableAudits(Request $request)
    {
        $post_data = $request->all();
        $user = Auth::user();

        $data = Common::filter($post_data);
        
        return response()->json([
            'status'   => '200',
            'message'  => 'Available audit fetch successfully.',
            'data'     => $data
        ], 200);
    }

    /**
     * Assign auditor to audit.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignAudits(Request $request)
    {
    }

    /**
     * My Audit Listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function myAudits(Request $request)
    {
    }

    /**
     * Export My Audit.
     *
     * @return \Illuminate\Http\Response
     */
    public function myAuditsExport(Request $request)
    {
    }

    /**
     * Audit History.
     *
     * @return \Illuminate\Http\Response
     */
    public function auditHistory(Request $request)
    {
    }

    /**
     * Common Filter.
     *
     * @return \Illuminate\Http\Response
     */
    public function commonFilter(Request $request)
    {
        $post_data = $request->all();
        $user = Auth::user();

        $data = Common::filter($post_data);
        
        return response()->json([
            'status'   => '200',
            'message'  => 'Data fetch successfully.',
            'data'     => $data
        ], 200);
    }

}
