<!DOCTYPE html>
<html>
<head>
    <title>Test Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] { 
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; 
        }
        button { 
            background: #007bff; color: white; padding: 10px 20px; 
            border: none; border-radius: 4px; cursor: pointer; 
        }
        .alert { 
            padding: 10px; margin: 10px 0; border-radius: 4px; 
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Admin Login</h1>
        
        <div class="info">
            <h3>Available Test Accounts:</h3>
            <p><strong>Account 1:</strong> admin@loasiptenan.com / admin123</p>
            <p><strong>Account 2:</strong> admin@test.com / password</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'admin@loasiptenan.com') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="admin123" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <div style="margin-top: 30px;">
            <a href="{{ route('home') }}">← Back to Home</a> | 
            <a href="{{ route('admin.create-admin') }}">Create/Check Admin Users</a>
        </div>
    </div>
</body>
</html>
