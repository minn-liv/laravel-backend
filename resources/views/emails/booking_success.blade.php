<!DOCTYPE html>
<html>

<head>
    <title>Form Submission</title>
</head>

<body>
    <h1>Thank you, {{ $name }}</h1>
    <p>We have received your submission.</p>
    <p>Attached is the file you submitted.</p>
    <img src="{{ $message->embed($attachmentPath) }}" alt="Submitted Image">
</body>

</html>