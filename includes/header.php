<?php
// Ensure session is started to check user status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logic for the Logo Link: Redirect based on auth status
$logo_link = isset($_SESSION['user_id']) ? "dashboard.php" : "index.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureCorp | Employee Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; color: #33475b; }
        .navbar { background: #1a202c; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card { border: none; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .btn-primary { background-color: #2563eb; border: none; padding: 10px 20px; border-radius: 8px; }
        .nav-link { font-weight: 500; margin-right: 15px; }
        .flag-box { border-left: 5px solid #10b981; background: #ecfdf5; padding: 15px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?php echo $logo_link; ?>">
        <span class="text-primary">Secure</span>Corp
    </a>

    <?php if (isset($_SESSION['user_id'])): ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="navbar-nav ms-auto">
        <a class="nav-link" href="search.php">Directory</a>
        <a class="nav-link" href="feedback.php">Support</a>
        <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">My Profile</a>
        <?php 
        $allowed_roles = ['admin', 'abhishek'];
        if (isset($_SESSION['username']) && in_array($_SESSION['username'], $allowed_roles)): 
        ?>
          <a class="nav-link text-warning" href="admin.php">Admin Panel</a>
        <?php endif; ?>
        <a class="nav-link text-danger" href="logout.php">Logout</a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</nav>

<div class="container">
