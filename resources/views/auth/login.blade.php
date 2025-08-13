<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
@if($errors->any())
    <div style="color: red;">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ route('login.post') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" autocomplete="email" required>
    <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
    <button type="submit">Login</button>
</form>
</body>
</html>
