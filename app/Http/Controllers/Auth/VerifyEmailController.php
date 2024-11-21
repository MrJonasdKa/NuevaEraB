<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class VerifyEmailController extends Controller
{/**
 * Mark the authenticated user's email address as verified.
 *
 * @param  \Laravel\Fortify\Http\Requests\VerifyEmailRequest  $request
 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
 */
    public function verify($id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return request()->wantsJson()
                ? new JsonResponse('', 204)
                : redirect('http://localhost:8100/verification-success'); // URL corregida
        }
        return request()->wantsJson()
            ? new JsonResponse('', 204)
            : redirect('http://localhost:8100/verification-success'); // URL corregida
    }

    
    public function resend() {
        request()->user()->sendEmailVerificationNotification();
        return response([
            'data' => [
                'message' => 'Request has been sent!',
            ]
        ]);
    }
}
