<?php
session_start();

$has_admin_cookie = (isset($_COOKIE['user_role']) && $_COOKIE['user_role'] === 'admin');
$has_admin_session = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
if (!$has_admin_cookie && !$has_admin_session) {
    // If not admin, show a fake 403 or redirect
    http_response_code(403);
    include('includes/header.php');
    echo '<div class="alert alert-danger shadow-sm animate__animated animate__shakeX">
            <h4 class="fw-bold">403 Access Denied</h4>
            <p>Your current role ('. htmlspecialchars($_COOKIE['user_role'] ?? 'guest') .') is not authorized to view this management console.</p>
            <hr><small>Reason: Insufficient permissions for resource /admin_panel.php</small>
          </div>';
    include('includes/footer.php');
    exit();
}

include('includes/header.php');
?>

<div class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-danger"><i class="bi bi-shield-lock"></i> Infrastructure Management</h2>
        <span class="badge bg-success">Admin Session: Active</span>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">Core Environment Variables</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Variable</th><th>Value</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>APP_DEBUG</td><td>TRUE</td><td><span class="badge bg-danger">Vulnerable</span></td></tr>
                            <tr><td>ROOT_PATH</td><td>C:\Users\Abhishek\SecureCorp_Intranet\</td><td><span class="badge bg-secondary">System</span></td></tr>
                            <tr><td>DB_USER</td><td>root_internal</td><td><span class="badge bg-info">Protected</span></td></tr>
                            <tr><td>JWT_SECRET</td><td>FLAG{ADMIN_PANEL_ACCESS}</td><td><span class="badge bg-warning text-dark">Critical</span></td></tr>
                            <tr><td>SSH_PORT</td><td>2222</td><td><span class="badge bg-success">Open</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white">Internal Network Nodes</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Gateway</span><code>10.0.5.1</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Mail Server</span><code>10.0.5.50</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Backup NAS</span><code>10.0.5.200</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Dev Box</span><code>127.0.0.1</code>
                        </li>
                    </ul>
                    <div class="mt-3 p-2 bg-light rounded border text-center">
                        <small class="text-muted">Last System Scan: 2 minutes ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white">Recent System Events (Internal Only)</div>
        <div class="card-body bg-black text-success p-0">
            <pre class="mb-0 p-3 small" style="max-height: 300px; overflow-y: auto; font-family: 'Courier New', monospace;">
[INFO] 12:45:01 - User 'Akshay Admin' updated security policies.
[WARN] 12:50:12 - Multiple failed login attempts on 'abhishek' account from 192.168.1.15.
[DEBUG] 12:55:44 - Memory buffer cleared for PII data processing.
[INFO] 13:02:10 - Backup initiated to /var/www/internal/backups/db_dump_v2.sql.
[ERROR] 13:10:05 - Connection timeout for node 10.0.5.50 (Mail Server).
[TRACE] 13:15:22 - Admin panel accessed by UID: 1 via cookie-bypass.
[NOTE] 13:20:00 - Dev password remains: hunter@123 for testing phase.
[INFO] 13:25:55 - Automated system maintenance completed successfully.
            </pre>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>