<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // توليد رابط التفعيل
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting("Hello {$notifiable->name},")
            ->line("Thanks for signing up! Please verify your email address by clicking the button below.")
            ->line("This link will expire in 60 minutes.")
            ->action('Verify Email', $verificationUrl)
            ->line("If you did not create an account, no further action is required.")
            ->salutation('Regards, <br> <strong>Courses </strong>')
            ->markdown('emails.verify-email', ['url' => $verificationUrl, 'user' => $notifiable->name]);
    }
}
