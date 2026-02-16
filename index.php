<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Mock database with specific roles
$mock_db = [
    ['id' => 'SID10001', 'username' => 'admin', 'password' => 'SuperSecret2026', 'role' => 'admin'],
    ['id' => 'SID10294', 'username' => 'abhishek', 'password' => 'hunter@123', 'role' => 'manager'],
    ['id' => 'SID10582', 'username' => 'john', 'password' => 'babayaga', 'role' => 'employee']
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $found_user = null;

    foreach ($mock_db as $u) {
        if ($u['username'] == $user && $u['password'] == $pass) {
            $found_user = $u;
            break;
        }
    }

    if ($found_user) {
        $_SESSION['user_id'] = $found_user['id'];
        $_SESSION['username'] = $found_user['username'];
        $_SESSION['role'] = $found_user['role']; // Store the role here
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Authentication Failed: Invalid credentials.";
    }
}

include('includes/header.php');
?>

<div class="row justify-content-center align-items-center animate__animated animate__fadeInDown" style="min-height: 70vh;">
    <div class="col-md-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold"><span class="text-primary">Secure</span>Corp</h2>
            <p class="text-muted">Internal Employee Access</p>
        </div>
        <div class="card p-4 shadow">
            <?php if($error) echo "<div class='alert alert-danger small animate__animated animate__shakeX'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 shadow-sm">Sign In</button>
<!--Test User Creds[john:babayaga] left here, remove them from production and
HINT For Students there is another set of creds you can discover-->
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>