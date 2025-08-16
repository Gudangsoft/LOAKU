<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Admin Login - LOA SIPTENAN</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>📋 Available Admin Accounts:</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Password</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>admin@admin.com</td>
                                                    <td>admin</td>
                                                    <td><button class="btn btn-sm btn-outline-primary" onclick="fillForm('admin@admin.com', 'admin')">Use</button></td>
                                                </tr>
                                                <tr>
                                                    <td>admin@test.com</td>
                                                    <td>password</td>
                                                    <td><button class="btn btn-sm btn-outline-primary" onclick="fillForm('admin@test.com', 'password')">Use</button></td>
                                                </tr>
                                                <tr>
                                                    <td>admin@loasiptenan.com</td>
                                                    <td>admin123</td>
                                                    <td><button class="btn btn-sm btn-outline-primary" onclick="fillForm('admin@loasiptenan.com', 'admin123')">Use</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6>🔗 Quick Links:</h6>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.debug-users') }}" class="btn btn-info btn-sm" target="_blank">Debug Users</a>
                                        <a href="{{ route('admin.test-auth') }}" class="btn btn-secondary btn-sm" target="_blank">Test Auth</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>🔐 Login Form:</h5>
                                <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" 
                                               value="{{ old('email', 'admin@admin.com') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" 
                                               value="admin" required>
                                        <div class="form-text">
                                            <input type="checkbox" id="showPassword"> Show password
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">🚀 Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillForm(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
        
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
