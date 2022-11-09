<?php

namespace App\Http\Controllers\Api;

use App\Models\FormData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class DashboardController extends Controller
{
    public function index(Request $request) {
        echo "hiii";exit;
    }
}
