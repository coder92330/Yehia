@component('mail::message')

<h1>{{ $data['subject'] }}</h1>

@component('mail::panel')
{{ $data['body'] }}
@endcomponent

<p>Thank you for using our application!</p>
@endcomponent
