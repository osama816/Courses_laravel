@component('mail::message')
{{-- Greeting --}}
# Hello {{ $user }},

Thanks for signing up! Please verify your email address by clicking the button below.

{{-- Verify Button --}}
@component('mail::button', ['url' => $url, 'color' => 'primary'])
Verify Email
@endcomponent

This verification link will expire in **60 minutes**.

If you did not create an account, no further action is required.

Thanks,<br>
**Your Company Name**
@endcomponent
