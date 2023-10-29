@component('mail::message')
    <p>We received a password reset request from you. Please click on the link below to reset your password:</p>
    @component('mail::button', ['url' => $uri])
        Reset Password
    @endcomponent
    <p>If you did not request a password change, please feel free to ignore this message.</p>
@endcomponent
