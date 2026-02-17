<!DOCTYPE html>
<html>
<body>
    <h2>You have a new query!</h2>
    <p><strong>Subject:</strong> {{ $contactUs->subject }}</p>
    <p><strong>Email:</strong> {{ $contactUs->email }}</p>
    <p><strong>Phone:</strong> {{ $contactUs->phone }}</p>
    <hr>
    <p><strong>Message:</strong></p>
    <p>{{ $contactUs->message }}</p>
</body>
</html>