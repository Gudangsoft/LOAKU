<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Routes Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Admin Routes Test</h3>
                    </div>
                    <div class="card-body">
                        <h5>Test Admin Register Route</h5>
                        <p>Click the button below to test the admin register route:</p>
                        
                        <div class="mb-3">
                            <a href="/admin/register" class="btn btn-primary me-2">
                                <i class="fas fa-user-plus"></i> Go to Admin Register
                            </a>
                            <a href="/admin/login" class="btn btn-secondary me-2">
                                <i class="fas fa-sign-in-alt"></i> Go to Admin Login
                            </a>
                            <a href="/admin/dashboard" class="btn btn-success">
                                <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                            </a>
                        </div>
                        
                        <hr>
                        
                        <h5>Quick Register Form Test</h5>
                        <form method="POST" action="/admin/register">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="Test Admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="testadmin@test.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="password123" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="password123" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" name="role">
                                    <option value="admin">Admin</option>
                                    <option value="moderator">Moderator</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Create Admin Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
