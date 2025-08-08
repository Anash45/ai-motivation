<!DOCTYPE html>
<html>
<head>
    <title>Email Test</title>
</head>
<body>
    <h1>System Email Test</h1>
    <p>This is a test HTML email sent from the system.</p>
    <p>Environment: {{ app()->environment() }}</p>
    <p>Time: {{ now()->toDateTimeString() }}</p>
    <p>Mail Driver: {{ config('mail.default') }}</p>
</body>
</html>