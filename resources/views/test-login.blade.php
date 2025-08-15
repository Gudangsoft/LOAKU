<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Test - LOAKU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Login Test</h2>
                
                <form method="POST" action="{{ route('login') }}"
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <select class="form-select" name="email" id="email" required>
                            <option value="">Pilih User</option>
                            <option value="admin@loaku.test">Admin (admin@loaku.test)</option>
                            <option value="user@loaku.test">Publisher User (user@loaku.test)</option>
                            <option value="publisher1@loaku.test">Publisher 1 (publisher1@loaku.test)</option>
                            <option value="publisher2@loaku.test">Publisher 2 (publisher2@loaku.test)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="password" required>
                        <small class="text-muted">Default password: password</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                
                <div class="mt-3 text-center">
                    <a href="/" class="btn btn-secondary">Kembali ke Home</a>
                </div>
                
                <div class="mt-4">
                    <h5>Info:</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Admin:</strong> admin@loaku.test</li>
                        <li class="list-group-item"><strong>Publisher 1:</strong> publisher1@loaku.test (Owner of Journal ID 2)</li>
                        <li class="list-group-item"><strong>Publisher 2:</strong> publisher2@loaku.test (Owner of Journal ID 3)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
