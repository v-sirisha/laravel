
@if(isset($ack))
	<h1>Hi, {{$firstName}}!</h1>

	<h2>Thank you for contacting us !!</h2>
	<h2>We will get back you soon....</h2>
	<h3>Note : This is system generated mail do not reply to this mail!</h3>

@else
	<h1>Name: {{ $firstName }} {{$lastName}}</h1>
	<h2>Email : {{ $email }}</h2>
	<h2>Subject : {{ $subject }}</h2>
	<h2>Message :{{ $bodyMessage }} </h2>
@endif