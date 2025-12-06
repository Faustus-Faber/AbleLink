<x-mail::message>
# Ablelink verification code

Use the one-time password below to finish your {{ $context }} flow.

<x-mail::panel>
{{ $code }}
</x-mail::panel>

This code expires in {{ $expiresInMinutes }} minutes. If you did not request it you can safely ignore this message.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
