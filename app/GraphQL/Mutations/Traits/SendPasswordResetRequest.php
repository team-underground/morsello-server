<?php

namespace App\GraphQL\Traits;

use App\Notifications\ResetPasswordRequestNotification;
use App\Notifications\ResetPasswordRequestSuccessNotification;
// use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

trait SendPasswordResetRequest
{
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordRequestNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendPasswordResetSuccessNotification()
    {
        $this->notify(new ResetPasswordRequestSuccessNotification);
    }
}
