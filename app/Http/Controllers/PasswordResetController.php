<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SendRecoveryCodeRequest;
use App\Http\Requests\Auth\PasswordRecoveryRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    use ApiResponse;
    public function forgotPassword(SendRecoveryCodeRequest $request) {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        $message = "If the provided email address exists you'll receive a password reset link";

        if(!$user ) {
            return $this->apiResponse(true, $message);
        }

        $status = Password::sendResetLink([
            'email' => $data['email']
        ]);
        

        return $this->apiResponse(true, $status);
    }

    public function resetPassword(PasswordRecoveryRequest $request) {
        $data = $request->validated();
        $user = User::whereEmail($data['email'])->first();

        if(!$user) {
            return $this->apiResponse(false, 'Invalid token or unauthorized request', 400);
        }

        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return match ($status) {
            Password::PASSWORD_RESET => $this->apiResponse(true, 'Password successfully updated'),
            default => $this->apiResponse(false, 'Invalid token or unauthorized request')
        };
    }
}