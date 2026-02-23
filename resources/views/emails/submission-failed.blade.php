<!DOCTYPE html>
<html>

<body>
    <h2>CRITICAL: Data Recovery Needed</h2>
    <p>The system failed to process a contact form submission. <strong>The data is below:</strong></p>

    <div style="border: 1px solid red; padding: 10px;">
        <p><strong>Name:</strong> {{ $contactFormData['name'] }}</p>
        <p><strong>Email:</strong> {{ $contactFormData['email'] }}</p>
        <p><strong>Phone:</strong> {{ $contactFormData['phone'] }}</p>
        <p><strong>Subject:</strong> {{ $contactFormData['subject'] }}</p>
        <p><strong>Message:</strong> {{ $contactFormData['message'] }}</p>
    </div>

    <p><strong>Error logged:</strong> {{ $errorMessage }}</p>
</body>

</html>