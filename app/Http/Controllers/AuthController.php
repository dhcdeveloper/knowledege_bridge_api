<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends BaseApiController
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->whereNull('deleted_at'),
        ],
            'password' => 'required|string|min:8', 
        ];
     
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->successResponse($user);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $params = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $user = User::getUserByMailPassword($params['email'], $params['password']);
        
        if (!empty($user)) {
            return $this->successResponse($user);
        } else {
            // ログイン失敗
            $user = User::getUserByMail($params['email']);
            if ($user) {
                return $this->errorResponse(__('message.error.user.wrong_password'));
            } else {
                return $this->errorResponse(__('message.error.user.wrong_email'));
            }
        }
    }
}
