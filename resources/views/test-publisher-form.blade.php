<!DOCTYPE html>
<html>
<head>
    <title>Publisher Test Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Test Publisher Creation</h2>
    
    <form action="{{ route('admin.publishers.store') }}" method="POST">
        @csrf
        
        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="Test Publisher" required>
        </p>
        
        <p>
            <label>Address:</label><br>
            <textarea name="address" required>Test Address 123, Test City</textarea>
        </p>
        
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="test@example.com" required>
        </p>
        
        <p>
            <label>Website:</label><br>
            <input type="url" name="website" value="https://example.com">
        </p>
        
        <p>
            <input type="submit" value="Create Publisher">
        </p>
    </form>
    
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
