<!DOCTYPE html>
<html>
<head>
    <title>Spies Sport</title>
</head>
<body>
    <h1>Welcome to Spies Sport</h1>

    @auth
        <a href="/dashboard">Go to Dashboard</a>
    @else
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    @endauth
</body>
</html>