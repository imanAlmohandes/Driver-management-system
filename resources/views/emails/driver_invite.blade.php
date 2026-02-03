<x-mail::message>
# 🚚 Driver Account Activation

Hello,

You’ve been invited to join **{{ config('app.name') }}** as a driver.

<x-mail::button :url="$activationUrl">
Activate Your Account
</x-mail::button>

**Notes:**
- Link is valid for **24 hours**
- Link works **one time only**

If you didn’t request this, you can ignore this email.

Thanks,
**{{ config('app.name') }}**
</x-mail::message>
