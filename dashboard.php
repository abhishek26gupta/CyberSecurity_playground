<?php
// 1. START SESSION AND LOGIC FIRST (Before any HTML output)
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

// A08: Software and Data Integrity Failures (Cookie Logic)
// Check if the cookie exists first to prevent "Undefined array key" warning
if (!isset($_COOKIE['user_role'])) {
    // Set the cookie for future requests
    setcookie("user_role", "employee", time() + 3600, "/");
    // Manually set it for the current page load so the logic below works immediately
    $_COOKIE['user_role'] = "employee";
}

// 2. NOW INCLUDE THE VISUAL HEADER
include('includes/header.php');
?>

<div class="animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-md-8">
            <h1 class="fw-bold mb-3">Hello, <?php echo ucfirst($_SESSION['username']); ?>!</h1>
            <p class="lead">Your workstation is ready. Check your tasks or find a colleague.</p>
            
            <?php 
            if($_SESSION['username'] == 'admin'): ?>
                <div class="flag-box animate__animated animate__pulse animate__infinite mb-3">
                    <h5 class="fw-bold text-success mb-1">🏁 FLAG{ADMIN_PANEL_BYPAS53d}</h5>
                    <small>You successfully manipulated the logic to gain Administrative access.</small>
                </div>
            <?php endif; ?>

            <?php 
            // A08: Cookie Manipulation Flag
            if (isset($_COOKIE['user_role']) && $_COOKIE['user_role'] === "admin"): ?>
                <div class="flag-box border-primary animate__animated animate__bounceIn">
                    <h5 class="fw-bold text-primary mb-1">🏁 FLAG{COOKIE_MANIPULATION_SUCCESS}</h5>
                    <small>High Alert: User privilege escalation detected via cookie tampering. Admin Panel at Risk</small>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4 text-end">
            <div class="card p-3 bg-white">
                <h6 class="text-muted mb-1">System Status</h6>
                <div class="d-flex align-items-center justify-content-end">
                    <span class="badge bg-success me-2">Online</span>
                    <span class="text-dark fw-bold">v2.0.4-LTS</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card p-4 text-center">
                <h3 class="fw-bold text-primary">Directory</h3>
                <p class="text-muted">Find contact details for any staff member in the organization.</p>
                <a href="search.php" class="btn btn-outline-primary w-100">Open Search</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 text-center">
                <h3 class="fw-bold text-success">Support</h3>
                <p class="text-muted">Need help? Send a secure message to our HR or IT department.</p>
                <a href="feedback.php" class="btn btn-outline-success w-100">Contact Support</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 text-center">
                <h3 class="fw-bold text-info">Profile</h3>
                <p class="text-muted">Manage your personal information and view your payroll data.</p>
                <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="btn btn-outline-info w-100">My Profile</a>
            </div>
        </div>
        <?php
        $allowed_roles = ['admin', 'abhishek'];
        if (isset($_SESSION['username']) && in_array($_SESSION['username'], $allowed_roles)): 
        ?>
        <div class="col-md-4 mb-4">
            <div class="card p-4 text-center">
                <h3 class="fw-bold text-danger">Avatar Sync</h3>
                <p class="text-muted">Choose Your Profile avatar form a wide range of collections</p>
                <a href="api_fetch.php" class="btn btn-outline-danger w-100">Select Avatar</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>