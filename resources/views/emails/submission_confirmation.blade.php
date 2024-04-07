@component('mail::message')
# Confirmation - Your Form Submission

Thank you for submitting the form. We have received your submission.

Please click the button below to verify your booking:
@component('mail::button', ['url' => $url])
Verify Booking
@endcomponent

Regards,<br>
Your Name
@endcomponent