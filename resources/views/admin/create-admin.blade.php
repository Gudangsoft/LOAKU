<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Admin User - LOA SIPTENAN</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .create-admin-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .admin-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
        }

        .card-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
        }

        .card-body {
            padding: 2.5rem;
        }

        .section-header {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin: 2rem 0 1.5rem;
        }

        .section-header h4 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
        }

        .section-header p {
            margin: 0;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .form-floating label {
            font-weight: 500;
        }

        .btn-create-admin {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-create-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .existing-users {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .user-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin: 0.5rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: between;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }

        .user-info h6 {
            margin: 0;
            font-weight: 600;
        }

        .user-info small {
            color: #6b7280;
        }

        .badge-role {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-super-admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-administrator {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-member {
            background: #d1fae5;
            color: #059669;
        }

        .quick-actions {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            margin: 0.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            transform: translateY(-1px);
        }

        .credential-box {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            margin: 0.5rem 0;
        }

        .credential-box code {
            background: #fff;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        .status-item {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.5);
        }

        .users-grid .user-card {
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .users-grid .user-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin: 1rem 0;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        .response-area {
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 10px;
            display: none;
        }

        .response-area.show {
            display: block;
        }

        .response-area.success {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #059669;
        }

        .response-area.error {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #dc2626;
        }
    </style>
</head>

<body>
    <div class="container create-admin-container">
        <div class="admin-card">
            <div class="card-header">
                <i class="fas fa-user-shield fa-3x mb-3"></i>
                <h2>Create Admin User</h2>
                <p>Buat dan kelola akun administrator untuk sistem LOA SIPTENAN</p>
            </div>

            <div class="card-body">
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="section-header">
                        <h4><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                        <p>Aksi cepat untuk mengelola sistem admin</p>
                    </div>
                    
                    <button class="btn btn-success quick-action-btn" onclick="createDefaultAdmin()">
                        <i class="fas fa-user-plus me-2"></i>Create Default Super Admin
                    </button>
                    
                    <button class="btn btn-info quick-action-btn" onclick="checkExistingUsers()">
                        <i class="fas fa-users me-2"></i>Check Existing Users
                    </button>
                    
                    <button class="btn btn-warning quick-action-btn" onclick="setupRoleSystem()">
                        <i class="fas fa-cogs me-2"></i>Setup Role System
                    </button>
                    
                    <button class="btn btn-secondary quick-action-btn" onclick="checkRoleSystem()">
                        <i class="fas fa-shield-alt me-2"></i>Check Role System
                    </button>
                    
                    <a href="/admin/login" class="btn btn-primary quick-action-btn">
                        <i class="fas fa-sign-in-alt me-2"></i>Go to Admin Login
                    </a>
                </div>

                <!-- Create Custom Admin Form -->
                <div class="section-header">
                    <h4><i class="fas fa-user-edit me-2"></i>Create Custom Admin</h4>
                    <p>Buat akun admin dengan informasi khusus</p>
                </div>

                <form id="createAdminForm" onsubmit="createCustomAdmin(event)">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="admin_name" name="name" placeholder="Nama Admin" required>
                                <label for="admin_name"><i class="fas fa-user me-2"></i>Nama Admin</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="admin_email" name="email" placeholder="Email Admin" required>
                                <label for="admin_email"><i class="fas fa-envelope me-2"></i>Email Admin</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="admin_password" name="password" placeholder="Password" required minlength="8">
                                <label for="admin_password"><i class="fas fa-lock me-2"></i>Password</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="admin_role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="administrator">Administrator</option>
                                    <option value="member">Member (Editor Jurnal)</option>
                                </select>
                                <label for="admin_role"><i class="fas fa-shield-alt me-2"></i>Role</label>
                                <div class="form-text">
                                    <small><strong>Administrator:</strong> Akses penuh untuk mengelola LOA dan data master<br>
                                    <strong>Member:</strong> Kelola publisher, jurnal, dan validasi LOA jurnal sendiri</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-create-admin">
                            <span class="loading">
                                <i class="fas fa-spinner fa-spin me-2"></i>Creating...
                            </span>
                            <span class="normal-text">
                                <i class="fas fa-user-plus me-2"></i>Create Custom Admin
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Response Area -->
                <div id="responseArea" class="response-area">
                    <div id="responseContent"></div>
                </div>

                <!-- Existing Users -->
                <div class="existing-users">
                    <div class="section-header">
                        <h4><i class="fas fa-users me-2"></i>Existing Admin Users</h4>
                        <p>Daftar pengguna admin yang sudah ada</p>
                    </div>
                    <div id="existingUsersList">
                        <div class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin me-2"></i>Loading users...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show response message
        function showResponse(message, type = 'success') {
            const responseArea = document.getElementById('responseArea');
            const responseContent = document.getElementById('responseContent');
            
            responseContent.innerHTML = message;
            responseArea.className = `response-area show ${type}`;
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                responseArea.className = 'response-area';
            }, 5000);
        }

        // Show loading state
        function setLoading(button, loading = true) {
            const loadingSpan = button.querySelector('.loading');
            const normalSpan = button.querySelector('.normal-text');
            
            if (loading) {
                loadingSpan.classList.add('show');
                normalSpan.style.display = 'none';
                button.disabled = true;
            } else {
                loadingSpan.classList.remove('show');
                normalSpan.style.display = 'inline';
                button.disabled = false;
            }
        }

        // Create default admin
        async function createDefaultAdmin() {
            const button = event.target;
            setLoading(button, true);

            try {
                const response = await fetch('/admin/create-admin-api', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showResponse(`
                        <h5><i class="fas fa-check-circle me-2"></i>Default Admin Created Successfully!</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>🔑 Super Admin Account:</strong></p>
                                <div class="credential-box">
                                    <p>📧 Email: <code>${data.admin_user.email}</code></p>
                                    <p>🔐 Password: <code>${data.admin_user.password}</code></p>
                                </div>
                            </div>
                            ${data.test_admin ? `
                            <div class="col-md-6">
                                <p><strong>👨‍💻 Test Admin Account:</strong></p>
                                <div class="credential-box">
                                    <p>📧 Email: <code>${data.test_admin.email}</code></p>
                                    <p>🔐 Password: <code>${data.test_admin.password}</code></p>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">✅ Total users in system: <strong>${data.all_users.length}</strong></p>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-2" onclick="checkExistingUsers()">
                                    <i class="fas fa-users me-1"></i>View All Users
                                </button>
                                <a href="/admin/login" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login Now
                                </a>
                            </div>
                        </div>
                    `, 'success');
                    
                    // Auto refresh existing users list after 1 second
                    setTimeout(() => {
                        checkExistingUsers();
                    }, 1000);
                } else {
                    showResponse(`
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Error Creating Admin</h5>
                        <p>${data.message || data.error || 'Failed to create admin user'}</p>
                        ${data.exception ? '<details class="mt-2"><summary>Technical Details</summary><pre>' + data.exception + '</pre></details>' : ''}
                        <div class="mt-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="createDefaultAdmin()">
                                <i class="fas fa-redo me-1"></i>Try Again
                            </button>
                        </div>
                    `, 'error');
                }
            } catch (error) {
                showResponse(`
                    <h5><i class="fas fa-times-circle me-2"></i>Network Error</h5>
                    <p>Could not connect to server: ${error.message}</p>
                    <p><small>Make sure your Laravel server is running on port 8000</small></p>
                    <div class="mt-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="createDefaultAdmin()">
                            <i class="fas fa-redo me-1"></i>Try Again
                        </button>
                    </div>
                `, 'error');
            } finally {
                setLoading(button, false);
            }
        }

        // Create custom admin
        async function createCustomAdmin(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            
            setLoading(submitButton, true);

            try {
                const response = await fetch('/admin/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: formData.get('name'),
                        email: formData.get('email'),
                        password: formData.get('password'),
                        password_confirmation: formData.get('password'),
                        role: formData.get('role')
                    })
                });

                if (response.ok || response.redirected) {
                    showResponse(`
                        <h5><i class="fas fa-check-circle me-2"></i>Admin Created Successfully!</h5>
                        <p>Admin user <strong>${formData.get('name')}</strong> has been created with role <strong>${formData.get('role')}</strong></p>
                        <p>You can now login with email: <strong>${formData.get('email')}</strong></p>
                    `, 'success');
                    form.reset();
                    checkExistingUsers();
                } else {
                    const data = await response.json();
                    let errorMessage = 'Please check the form fields';
                    
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join('<br>');
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    
                    showResponse(`
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Validation Error</h5>
                        <p>${errorMessage}</p>
                    `, 'error');
                }
            } catch (error) {
                showResponse(`
                    <h5><i class="fas fa-times-circle me-2"></i>Error</h5>
                    <p>Failed to create admin: ${error.message}</p>
                `, 'error');
            } finally {
                setLoading(submitButton, false);
            }
        }

        // Check existing users  
        async function checkExistingUsers() {
            const usersList = document.getElementById('existingUsersList');
            usersList.innerHTML = '<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Loading users...</div>';

            try {
                const response = await fetch('/admin/debug-users', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.users && data.users.length > 0) {
                    usersList.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Found ${data.total_users} users</h6>
                            <button class="btn btn-sm btn-outline-primary" onclick="checkExistingUsers()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                        <div class="users-grid">
                            ${data.users.map(user => `
                                <div class="user-card d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar">${user.name.charAt(0).toUpperCase()}</div>
                                        <div class="user-info ms-3">
                                            <h6 class="mb-0">${user.name}</h6>
                                            <small class="text-muted">${user.email}</small>
                                            <br>
                                            ${user.email_verified_at ? 
                                                '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Verified</small>' : 
                                                '<small class="text-warning"><i class="fas fa-clock me-1"></i>Unverified</small>'
                                            }
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge-role badge-${user.is_admin ? 'administrator' : 'member'} mb-1">
                                            ${user.is_admin ? 
                                                (user.role === 'super_admin' ? 'Super Admin' : 'Admin') : 
                                                'Member'
                                            }
                                        </span>
                                        <br>
                                        <small class="text-muted">ID: ${user.id}</small>
                                        ${user.created_at ? '<br><small class="text-muted">Created: ' + new Date(user.created_at).toLocaleDateString() + '</small>' : ''}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else if (response.ok && (!data.users || data.users.length === 0)) {
                    usersList.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                            <h6>No users found in the system</h6>
                            <p class="mb-3">Create your first admin user to get started</p>
                            <button class="btn btn-primary btn-sm" onclick="createDefaultAdmin()">
                                <i class="fas fa-user-plus me-1"></i>Create First Admin
                            </button>
                        </div>
                    `;
                } else {
                    usersList.innerHTML = `
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <h6>Error loading users</h6>
                            <p class="mb-3">${data.message || data.error || 'Failed to fetch users from database'}</p>
                            <button class="btn btn-outline-primary btn-sm" onclick="checkExistingUsers()">
                                <i class="fas fa-redo me-1"></i>Try Again
                            </button>
                        </div>
                    `;
                }
            } catch (error) {
                usersList.innerHTML = `
                    <div class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h6>Connection Error</h6>
                        <p class="mb-3">Error loading users: ${error.message}</p>
                        <p><small>Make sure your Laravel server is running</small></p>
                        <button class="btn btn-outline-primary btn-sm" onclick="checkExistingUsers()">
                            <i class="fas fa-redo me-1"></i>Try Again
                        </button>
                    </div>
                `;
            }
        }

        // Check role system
        async function checkRoleSystem() {
            const button = event.target;
            setLoading(button, true);

            try {
                const response = await fetch('/admin/test-roles', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    const hasRoles = data.roles && data.roles.length > 0;
                    const hasPermissions = data.permissions && data.permissions.length > 0;
                    const hasUsers = data.users && data.users.length > 0;
                    
                    showResponse(`
                        <h5><i class="fas fa-info-circle me-2"></i>Role System Status</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="status-item ${hasRoles ? 'text-success' : 'text-warning'}">
                                    <i class="fas fa-${hasRoles ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                                    <strong>Roles:</strong> ${data.roles?.length || 0}
                                </div>
                                ${hasRoles ? '<small class="text-muted">' + data.roles.map(role => role.name).join(', ') + '</small>' : ''}
                            </div>
                            <div class="col-md-4">
                                <div class="status-item ${hasPermissions ? 'text-success' : 'text-warning'}">
                                    <i class="fas fa-${hasPermissions ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                                    <strong>Permissions:</strong> ${data.permissions?.length || 0}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="status-item ${hasUsers ? 'text-success' : 'text-warning'}">
                                    <i class="fas fa-${hasUsers ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                                    <strong>Users:</strong> ${data.users?.length || 0}
                                </div>
                            </div>
                        </div>
                        <hr>
                        ${hasRoles && hasPermissions ? 
                            '<div class="alert alert-success"><i class="fas fa-thumbs-up me-2"></i>✅ Role system is properly configured!</div>' : 
                            '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>⚠️ Role system needs setup. Click "Setup Role System" button.</div>'
                        }
                        ${data.error ? '<div class="alert alert-danger mt-2"><strong>Error:</strong> ' + data.error + '</div>' : ''}
                        <div class="mt-2">
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="checkRoleSystem()">
                                <i class="fas fa-sync me-1"></i>Refresh Status
                            </button>
                            ${!hasRoles || !hasPermissions ? 
                                '<button class="btn btn-warning btn-sm" onclick="setupRoleSystem()"><i class="fas fa-cog me-1"></i>Setup Now</button>' : ''
                            }
                        </div>
                    `, hasRoles && hasPermissions ? 'success' : 'warning');
                } else {
                    showResponse(`
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>System Check Error</h5>
                        <p>${data.message || data.error || 'Could not check role system status'}</p>
                        <div class="mt-2">
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="checkRoleSystem()">
                                <i class="fas fa-redo me-1"></i>Try Again
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="setupRoleSystem()">
                                <i class="fas fa-cog me-1"></i>Setup Role System
                            </button>
                        </div>
                    `, 'error');
                }
            } catch (error) {
                showResponse(`
                    <h5><i class="fas fa-times-circle me-2"></i>Connection Error</h5>
                    <p>Network error: ${error.message}</p>
                    <p><small>Make sure your Laravel server is running on port 8000</small></p>
                    <div class="mt-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="checkRoleSystem()">
                            <i class="fas fa-redo me-1"></i>Try Again
                        </button>
                    </div>
                `, 'error');
            } finally {
                setLoading(button, false);
            }
        }

        // Setup role system
        async function setupRoleSystem() {
            const button = event.target;
            setLoading(button, true);

            try {
                const response = await fetch('/admin/setup-roles', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showResponse(`
                        <h5><i class="fas fa-check-circle me-2"></i>Role System Setup Complete!</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>🎯 Roles Created:</strong></p>
                                <ul>
                                    ${data.setup_info.roles ? data.setup_info.roles.map(role => `<li>${role}</li>`).join('') : '<li>Default roles</li>'}
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <p><strong>🔐 Permissions Created:</strong></p>
                                <p>Total: ${data.setup_info.permissions_count || '26'} permissions</p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-0">✅ ${data.message}</p>
                        <div class="mt-2">
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="checkRoleSystem()">
                                <i class="fas fa-search me-1"></i>Verify Setup
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="checkExistingUsers()">
                                <i class="fas fa-users me-1"></i>Check Users
                            </button>
                        </div>
                    `, 'success');
                } else {
                    showResponse(`
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Setup Error</h5>
                        <p>${data.message || data.error || 'Failed to setup role system'}</p>
                        ${data.exception ? '<details><summary>Technical Details</summary><pre>' + data.exception + '</pre></details>' : ''}
                        <hr>
                        <p><strong>Manual Setup:</strong></p>
                        <p>Run this command in terminal: <code>php artisan db:seed --class=RolePermissionSeeder</code></p>
                    `, 'error');
                }
            } catch (error) {
                showResponse(`
                    <h5><i class="fas fa-times-circle me-2"></i>Network Error</h5>
                    <p>Could not connect to setup endpoint: ${error.message}</p>
                    <hr>
                    <p><strong>Manual Setup:</strong></p>
                    <p>Run this command in terminal: <code>php artisan db:seed --class=RolePermissionSeeder</code></p>
                `, 'error');
            } finally {
                setLoading(button, false);
            }
        }

        // Load existing users on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkExistingUsers();
        });
    </script>
</body>
</html>
