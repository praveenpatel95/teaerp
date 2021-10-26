<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 401;

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            if (User::where('email', '=', $request->email)->exists()) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $user = Auth::user();
                    if (Auth::user()->status == 1) {
                        $data['status'] = true;
                        $statusCode = $this->successStatus;
                        $data['code'] = $statusCode;
                        $data['user'] = User::with('salesman')->find($user->id);
                        $data['user']['token'] = $user->createToken('Teaerp')->accessToken;
                    } else {
                        $data['status'] = false;
                        $statusCode = $this->errorStatus;
                        $data['code'] = $statusCode;
                        $data['msg'] = 'user has no authorized for login';
                    }
                    return response()->json(['data' => $data], $statusCode);
                } else {
                    $data['status'] = false;
                    $statusCode = $this->errorStatus;
                    $data['code'] = $statusCode;
                    $data['msg'] = 'Login Failed';
                }
                return response()->json(['data' => $data], $statusCode);
            } else {
                $data['status'] = false;
                $statusCode = $this->errorStatus;
                $data['code'] = $statusCode;
                $data['msg'] = 'These credentials do not match our records.';
            }
            return response()->json(['data' => $data], $statusCode);
        }
    }
}
