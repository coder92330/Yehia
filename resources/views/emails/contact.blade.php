@component('mail::message')

<h1>Contact Form</h1>

@component('mail::panel')
{{ $data['message'] }}
@endcomponent

<p>Thank you for using our application!</p>
@endcomponent
